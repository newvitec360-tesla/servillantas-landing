<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\RolRepository;

final class RolService
{
    private RolRepository $repo;

    public function __construct()
    {
        $this->repo = new RolRepository();
    }

    public function crear(array $data): array
    {
        $nombre = trim($data['nombre'] ?? '');
        if (empty($nombre)) {
            return ['ok' => false, 'errors' => ['El nombre del rol es obligatorio.']];
        }
        if ($this->repo->nameExists($nombre)) {
            return ['ok' => false, 'errors' => ['Ya existe un rol con ese nombre.']];
        }

        try {
            $id = $this->repo->create($nombre, trim($data['descripcion'] ?? ''));
            return ['ok' => true, 'id' => $id];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    public function actualizar(int $id, array $data): array
    {
        $nombre = trim($data['nombre'] ?? '');
        if (empty($nombre)) {
            return ['ok' => false, 'errors' => ['El nombre del rol es obligatorio.']];
        }
        if ($this->repo->nameExists($nombre, $id)) {
            return ['ok' => false, 'errors' => ['Ya existe otro rol con ese nombre.']];
        }

        try {
            $this->repo->update($id, $nombre, trim($data['descripcion'] ?? ''));
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    public function eliminar(int $id): array
    {
        $rol = $this->repo->findById($id);
        if (!$rol) {
            return ['ok' => false, 'errors' => ['Rol no encontrado.']];
        }
        // Protect admin role
        if (stripos($rol['nombre'], 'administrador') !== false) {
            return ['ok' => false, 'errors' => ['No se puede eliminar el rol de administrador.']];
        }
        $count = $this->repo->countUsuariosByRol($id);
        if ($count > 0) {
            return ['ok' => false, 'errors' => ["No se puede eliminar: hay {$count} usuario(s) asignados a este rol."]];
        }

        try {
            $this->repo->delete($id);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error: ' . $e->getMessage()]];
        }
    }

    public function syncPermisos(int $rolId, array $permisoIds): array
    {
        $rol = $this->repo->findById($rolId);
        if (!$rol) {
            return ['ok' => false, 'errors' => ['Rol no encontrado.']];
        }

        try {
            $this->repo->syncPermisos($rolId, $permisoIds);
            return ['ok' => true];
        } catch (\PDOException $e) {
            return ['ok' => false, 'errors' => ['Error al sincronizar permisos: ' . $e->getMessage()]];
        }
    }
}
