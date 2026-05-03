<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? [
            'id' => 1,
            'nombre' => 'Usuario demo',
            'rol' => 'administrador_general',
        ];
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (!$user) return false;

        $permissions = config('permissions.' . $user['rol'], []);
        return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
    }
}
