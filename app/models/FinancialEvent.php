<?php

class FinancialEvent
{
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO financial_events (event_date, direction, type, amount, description, details, related_entity_type, related_entity_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['event_date'],
            $data['direction'],
            $data['type'],
            $data['amount'],
            $data['description'],
            $data['details'],
            $data['related_entity_type'],
            $data['related_entity_id'],
            $data['created_by'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function list(array $filters = []): array
    {
        $pdo = Database::getConnection();
        $where = [];
        $params = [];

        if (!empty($filters['start'])) {
            $where[] = 'event_date >= ?';
            $params[] = $filters['start'];
        }
        if (!empty($filters['end'])) {
            $where[] = 'event_date <= ?';
            $params[] = $filters['end'];
        }
        if (!empty($filters['type'])) {
            $where[] = 'type = ?';
            $params[] = $filters['type'];
        }
        if (!empty($filters['direction'])) {
            $where[] = 'direction = ?';
            $params[] = $filters['direction'];
        }

        $sql = 'SELECT * FROM financial_events';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY event_date DESC, id DESC';
        if (!empty($filters['limit'])) {
            $sql .= ' LIMIT ' . (int)$filters['limit'] . ' OFFSET ' . (int)($filters['offset'] ?? 0);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function count(array $filters = []): int
    {
        $pdo = Database::getConnection();
        $where = [];
        $params = [];

        if (!empty($filters['start'])) {
            $where[] = 'event_date >= ?';
            $params[] = $filters['start'];
        }
        if (!empty($filters['end'])) {
            $where[] = 'event_date <= ?';
            $params[] = $filters['end'];
        }
        if (!empty($filters['type'])) {
            $where[] = 'type = ?';
            $params[] = $filters['type'];
        }
        if (!empty($filters['direction'])) {
            $where[] = 'direction = ?';
            $params[] = $filters['direction'];
        }

        $sql = 'SELECT COUNT(*) FROM financial_events';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public static function sumByDirectionAndTypes(string $direction, array $types, ?string $start, ?string $end): float
    {
        $pdo = Database::getConnection();
        $in = implode(',', array_fill(0, count($types), '?'));
        $params = $types;
        $where = 'direction = ? AND type IN (' . $in . ')';
        array_unshift($params, $direction);
        if ($start) {
            $where .= ' AND event_date >= ?';
            $params[] = $start;
        }
        if ($end) {
            $where .= ' AND event_date <= ?';
            $params[] = $end;
        }
        $stmt = $pdo->prepare('SELECT COALESCE(SUM(amount),0) as total FROM financial_events WHERE ' . $where);
        $stmt->execute($params);
        return (float)$stmt->fetchColumn();
    }

    public static function balance(): float
    {
        $pdo = Database::getConnection();
        $in = $pdo->query('SELECT COALESCE(SUM(amount),0) FROM financial_events WHERE direction = "IN"')->fetchColumn();
        $out = $pdo->query('SELECT COALESCE(SUM(amount),0) FROM financial_events WHERE direction = "OUT"')->fetchColumn();
        return (float)$in - (float)$out;
    }

    public static function monthlyRevenueSeries(int $months = 12): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT DATE_FORMAT(event_date, "%Y-%m") as month, SUM(amount) as total FROM financial_events WHERE direction = "IN" AND type IN ("CONTRACT_ENTRY","RECURRING_PAYMENT") GROUP BY month ORDER BY month DESC LIMIT ?');
        $stmt->bindValue(1, $months, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $series = [];
        foreach (array_reverse($rows) as $row) {
            $series[] = ['month' => $row['month'], 'total' => (float)$row['total']];
        }
        return $series;
    }
}
