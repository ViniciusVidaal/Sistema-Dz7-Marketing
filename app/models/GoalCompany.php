<?php

class GoalCompany
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM goals_company ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM goals_company WHERE id = ?');
        $stmt->execute([$id]);
        $goal = $stmt->fetch();
        return $goal ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO goals_company (name, target_value, current_value, start_date, end_date, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['name'],
            $data['target_value'],
            $data['current_value'],
            $data['start_date'],
            $data['end_date'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE goals_company SET name = ?, target_value = ?, current_value = ?, start_date = ?, end_date = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([
            $data['name'],
            $data['target_value'],
            $data['current_value'],
            $data['start_date'],
            $data['end_date'],
            $id,
        ]);
    }
}
