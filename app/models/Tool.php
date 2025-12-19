<?php

class Tool
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM tools ORDER BY next_due_date ASC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM tools WHERE id = ?');
        $stmt->execute([$id]);
        $tool = $stmt->fetch();
        return $tool ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO tools (name, value, start_date, next_due_date, card_last4, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['name'],
            $data['value'],
            $data['start_date'],
            $data['next_due_date'],
            $data['card_last4'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE tools SET name = ?, value = ?, start_date = ?, next_due_date = ?, card_last4 = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([
            $data['name'],
            $data['value'],
            $data['start_date'],
            $data['next_due_date'],
            $data['card_last4'],
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM tools WHERE id = ?');
        $stmt->execute([$id]);
    }
}
