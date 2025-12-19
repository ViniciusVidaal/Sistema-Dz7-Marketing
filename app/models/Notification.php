<?php

class Notification
{
    private static function excludedTypes(?string $role): array
    {
        if ($role === 'EMPLOYEE') {
            return ['APPROVAL_REQUEST', 'PASSWORD_RESET'];
        }
        return [];
    }

    public static function create(array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT IGNORE INTO notifications (type, title, body, reference_type, reference_id, notify_date, unique_key, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['type'],
            $data['title'],
            $data['body'],
            $data['reference_type'],
            $data['reference_id'],
            $data['notify_date'],
            $data['unique_key'],
        ]);
    }

    public static function listForUser(int $userId, ?string $role = null): array
    {
        $pdo = Database::getConnection();
        $exclude = self::excludedTypes($role);
        $sql = 'SELECT n.*, nr.read_at FROM notifications n LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_id = ?';
        $params = [$userId];
        if ($exclude) {
            $in = implode(',', array_fill(0, count($exclude), '?'));
            $sql .= ' WHERE n.type NOT IN (' . $in . ')';
            $params = array_merge($params, $exclude);
        }
        $sql .= ' ORDER BY n.created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function unreadCount(int $userId, ?string $role = null): int
    {
        $pdo = Database::getConnection();
        $exclude = self::excludedTypes($role);
        $sql = 'SELECT COUNT(*) FROM notifications n LEFT JOIN notification_reads nr ON n.id = nr.notification_id AND nr.user_id = ? WHERE nr.read_at IS NULL';
        $params = [$userId];
        if ($exclude) {
            $in = implode(',', array_fill(0, count($exclude), '?'));
            $sql .= ' AND n.type NOT IN (' . $in . ')';
            $params = array_merge($params, $exclude);
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public static function markRead(int $notificationId, int $userId): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO notification_reads (notification_id, user_id, read_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE read_at = NOW()');
        $stmt->execute([$notificationId, $userId]);
    }
}
