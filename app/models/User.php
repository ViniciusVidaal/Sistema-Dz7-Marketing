<?php

class User
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM users ORDER BY name');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, position, must_change_password, active, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())');
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password_hash'],
            $data['role'],
            $data['position'],
            $data['must_change_password'] ?? 1,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, role = ?, position = ?, active = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['role'],
            $data['position'],
            $data['active'] ?? 1,
            $id,
        ]);
    }

    public static function setPassword(int $id, string $passwordHash, int $mustChange = 1): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ?, must_change_password = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$passwordHash, $mustChange, $id]);
    }

    public static function createPasswordResetRequest(int $userId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO password_resets_requests (user_id, status, created_at) VALUES (?, "PENDING", NOW())');
        $stmt->execute([$userId]);
    }

    public static function markPasswordResetDone(int $userId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE password_resets_requests SET status = "DONE", resolved_at = NOW() WHERE user_id = ? AND status = "PENDING"');
        $stmt->execute([$userId]);
    }

    public static function pendingResetRequests(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT pr.*, u.name, u.email FROM password_resets_requests pr JOIN users u ON pr.user_id = u.id WHERE pr.status = "PENDING" ORDER BY pr.created_at DESC');
        return $stmt->fetchAll();
    }
}
