<?php

class GoalPersonal
{
    public static function listByUser(int $userId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM goals_personal WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO goals_personal (user_id, name, type, target_value, current_value, start_date, end_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['user_id'],
            $data['name'],
            $data['type'],
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
        $stmt = $pdo->prepare('UPDATE goals_personal SET name = ?, type = ?, target_value = ?, current_value = ?, start_date = ?, end_date = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([
            $data['name'],
            $data['type'],
            $data['target_value'],
            $data['current_value'],
            $data['start_date'],
            $data['end_date'],
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM goals_personal WHERE id = ?');
        $stmt->execute([$id]);
    }
}
