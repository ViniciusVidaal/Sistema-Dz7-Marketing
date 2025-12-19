<?php

class Middleware
{
    public static function requireAuth(): void
    {
        if (!Auth::check()) {
            redirect('/login');
        }
        $user = Auth::user();
        if ($user && (int)$user['must_change_password'] === 1 && $_SERVER['REQUEST_URI'] !== '/profile/password') {
            redirect('/profile/password');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        $user = Auth::user();
        if (!$user || $user['role'] !== 'ADMIN') {
            redirect('/dashboard');
        }
    }

    public static function requireEmployeeOrAdmin(): void
    {
        self::requireAuth();
        $user = Auth::user();
        if (!$user || !in_array($user['role'], ['ADMIN', 'EMPLOYEE'], true)) {
            redirect('/login');
        }
    }
}
