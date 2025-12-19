<?php

class Investment
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT i.*, u.name as created_by_name FROM investments i LEFT JOIN users u ON i.created_by = u.id ORDER BY i.created_at DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM investments WHERE id = ?');
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO investments (name, description, value, priority, due_date, status, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['value'],
            $data['priority'],
            $data['due_date'],
            $data['status'],
            $data['created_by'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE investments SET name = ?, description = ?, value = ?, priority = ?, due_date = ?, status = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['value'],
            $data['priority'],
            $data['due_date'],
            $data['status'],
            $id,
        ]);
    }
}
