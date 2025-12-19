<?php

class Approval
{
    public static function createOrUpdatePending(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id FROM approvals WHERE entity_type = ? AND entity_id = ? AND status = "PENDING" LIMIT 1');
        $stmt->execute([$data['entity_type'], $data['entity_id']]);
        $existing = $stmt->fetchColumn();

        if ($existing) {
            $update = $pdo->prepare('UPDATE approvals SET requested_by = ?, requested_at = NOW(), summary = ?, before_json = ?, after_json = ?, status = "PENDING" WHERE id = ?');
            $update->execute([
                $data['requested_by'],
                $data['summary'],
                $data['before_json'],
                $data['after_json'],
                $existing,
            ]);
            return (int)$existing;
        }

        $insert = $pdo->prepare('INSERT INTO approvals (entity_type, entity_id, status, requested_by, requested_at, summary, before_json, after_json) VALUES (?, ?, "PENDING", ?, NOW(), ?, ?, ?)');
        $insert->execute([
            $data['entity_type'],
            $data['entity_id'],
            $data['requested_by'],
            $data['summary'],
            $data['before_json'],
            $data['after_json'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function pending(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT a.*, u.name as requested_by_name FROM approvals a JOIN users u ON a.requested_by = u.id WHERE a.status = "PENDING" ORDER BY a.requested_at DESC');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM approvals WHERE id = ?');
        $stmt->execute([$id]);
        $approval = $stmt->fetch();
        return $approval ?: null;
    }

    public static function setStatus(int $id, string $status, int $adminId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE approvals SET status = ?, approved_by = ?, approved_at = NOW() WHERE id = ?');
        $stmt->execute([$status, $adminId, $id]);
    }

    public static function expirePending(string $entityType, int $entityId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE approvals SET status = "EXPIRED", approved_at = NOW() WHERE entity_type = ? AND entity_id = ? AND status = "PENDING"');
        $stmt->execute([$entityType, $entityId]);
    }
}
