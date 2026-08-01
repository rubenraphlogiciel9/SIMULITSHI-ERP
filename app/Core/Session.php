<?php

namespace App\Core;

class Session
{
    /**
     * Initialise la session avec des paramètres de sécurité stricts
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_samesite', 'Lax');

            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::init();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::init();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::init();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy(): void
    {
        self::init();
        session_unset();
        session_destroy();
    }
}