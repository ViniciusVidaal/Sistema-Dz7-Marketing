<?php

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND active = 1 LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['password_hash'])) {
            $legacyHash = hash('sha256', $password);
            if (hash_equals($legacyHash, $user['password_hash'])) {
                $newHash = password_hash($password, PASSWORD_BCRYPT);
                User::setPassword((int)$user['id'], $newHash, (int)$user['must_change_password']);
            } else {
                return false;
            }
        }
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'position' => $user['position'],
            'must_change_password' => (int)$user['must_change_password'],
        ];
        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function refreshUser(int $userId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user) {
            return;
        }
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'position' => $user['position'],
            'must_change_password' => (int)$user['must_change_password'],
        ];
    }
}
