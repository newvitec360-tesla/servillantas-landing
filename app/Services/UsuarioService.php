<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UsuarioRepository;

final class UsuarioService
{
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->repo = new UsuarioRepository();
    }

    /**
     * Create a new user with validation
     */
    public function crear(array $data): array
    {
        $errors = $this->validar($data, isNew: true);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        // Check unique email
        $existing = $this->repo->findByEmail(trim($data['correo']));
        if ($existing) {
            return ['ok' => false, 'errors' => ['El correo ya está registrado en el sistema.']];
        }

        $userData = [
            'nombre' => trim($data['nombre']),
            'correo' => strtolower(trim($data['correo'])),
            'telefono' => trim($data['telefono'] ?? ''),
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'rol_id' => (int)$data['rol_id'],
            'estado' => 'activo',
        ];

        try {
            $id = $this->repo->insert($userData);
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al crear usuario: ' . $e->getMessage()]];
        }
    }

    /**
     * Update user data (without password)
     */
    public function actualizar(int $id, array $data): array
    {
        $errors = $this->validar($data, isNew: false);
        if (!empty($errors)) {
            return ['ok' => false, 'errors' => $errors];
        }

        // Check unique email (exclude current user)
        $existing = $this->repo->findByEmail(trim($data['correo']));
        if ($existing && (int)$existing['id'] !== $id) {
            return ['ok' => false, 'errors' => ['El correo ya está en uso por otro usuario.']];
        }

        $userData = [
            'nombre' => trim($data['nombre']),
            'correo' => strtolower(trim($data['correo'])),
            'telefono' => trim($data['telefono'] ?? ''),
            'rol_id' => (int)$data['rol_id'],
        ];

        try {
            $this->repo->update($id, $userData);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al actualizar: ' . $e->getMessage()]];
        }
    }

    /**
     * Toggle user estado (activo/inactivo/bloqueado)
     */
    public function toggleEstado(int $id, string $nuevoEstado): array
    {
        $allowed = ['activo', 'inactivo', 'bloqueado'];
        if (!in_array($nuevoEstado, $allowed, true)) {
            return ['ok' => false, 'errors' => ['Estado inválido.']];
        }

        try {
            $this->repo->updateEstado($id, $nuevoEstado);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    /**
     * Reset password to a new random one
     */
    public function resetPassword(int $id): array
    {
        $newPassword = $this->generatePassword();
        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

        try {
            $this->repo->updatePassword($id, $hash);
            return ['ok' => true, 'new_password' => $newPassword];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    /**
     * Delete user (only if not the last admin)
     */
    public function eliminar(int $id): array
    {
        $user = $this->repo->findById($id);
        if (!$user) {
            return ['ok' => false, 'errors' => ['Usuario no encontrado.']];
        }

        // Prevent deleting yourself
        if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === $id) {
            return ['ok' => false, 'errors' => ['No puedes eliminar tu propia cuenta.']];
        }

        try {
            $this->repo->delete($id);
            return ['ok' => true];
        } catch (\PDOException $e) {
            // FK constraint - user has related records
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                // Soft delete instead
                $this->repo->updateEstado($id, 'inactivo');
                return ['ok' => true, 'warning' => 'Usuario desactivado (tiene registros asociados).'];
            }
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    /**
     * Validate user input
     */
    private function validar(array $data, bool $isNew = true): array
    {
        $errors = [];

        if (empty(trim($data['nombre'] ?? ''))) {
            $errors[] = 'El nombre es obligatorio.';
        }

        $correo = trim($data['correo'] ?? '');
        if (empty($correo)) {
            $errors[] = 'El correo es obligatorio.';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo no tiene un formato válido.';
        }

        if (empty($data['rol_id'] ?? 0)) {
            $errors[] = 'Debe seleccionar un rol.';
        }

        if ($isNew) {
            $password = $data['password'] ?? '';
            if (strlen($password) < 8) {
                $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
            }
        }

        return $errors;
    }

    /**
     * Generate a secure random password
     */
    private function generatePassword(int $length = 12): string
    {
        $chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
}
