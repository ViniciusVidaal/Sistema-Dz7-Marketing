<?php

class Lead
{
    public static function list(array $filters = []): array
    {
        $pdo = Database::getConnection();
        $where = [];
        $params = [];

        if (!empty($filters['search'])) {
            $where[] = '(name LIKE ? OR business_gmn LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['origin'])) {
            $where[] = 'origin = ?';
            $params[] = $filters['origin'];
        }
        if (!empty($filters['payment_type'])) {
            $where[] = 'payment_type = ?';
            $params[] = $filters['payment_type'];
        }
        if (!empty($filters['service'])) {
            $where[] = 'id IN (SELECT lead_id FROM lead_services WHERE service_code = ?)';
            $params[] = $filters['service'];
        }

        $sql = 'SELECT * FROM leads';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC';
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

        if (!empty($filters['search'])) {
            $where[] = '(name LIKE ? OR business_gmn LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['origin'])) {
            $where[] = 'origin = ?';
            $params[] = $filters['origin'];
        }
        if (!empty($filters['payment_type'])) {
            $where[] = 'payment_type = ?';
            $params[] = $filters['payment_type'];
        }
        if (!empty($filters['service'])) {
            $where[] = 'id IN (SELECT lead_id FROM lead_services WHERE service_code = ?)';
            $params[] = $filters['service'];
        }

        $sql = 'SELECT COUNT(*) FROM leads';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM leads WHERE id = ?');
        $stmt->execute([$id]);
        $lead = $stmt->fetch();
        if (!$lead) {
            return null;
        }
        $lead['services'] = self::services($id);
        return $lead;
    }

    public static function services(int $leadId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT service_code FROM lead_services WHERE lead_id = ?');
        $stmt->execute([$leadId]);
        return array_column($stmt->fetchAll(), 'service_code');
    }

    public static function create(array $data, array $services): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO leads (name, phone, email, contact_name, business_gmn, city, service_location, origin, converted_by, payment_type, entry_value, monthly_value, months, pay_day, contract_start, contract_end, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['email'],
            $data['contact_name'],
            $data['business_gmn'],
            $data['city'],
            $data['service_location'],
            $data['origin'],
            $data['converted_by'],
            $data['payment_type'],
            $data['entry_value'],
            $data['monthly_value'],
            $data['months'],
            $data['pay_day'],
            $data['contract_start'],
            $data['contract_end'],
            $data['created_by'],
        ]);
        $leadId = (int)$pdo->lastInsertId();

        self::syncServices($leadId, $services);
        return $leadId;
    }

    public static function update(int $id, array $data, array $services): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE leads SET name = ?, phone = ?, email = ?, contact_name = ?, business_gmn = ?, city = ?, service_location = ?, origin = ?, converted_by = ?, payment_type = ?, entry_value = ?, monthly_value = ?, months = ?, pay_day = ?, contract_start = ?, contract_end = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['email'],
            $data['contact_name'],
            $data['business_gmn'],
            $data['city'],
            $data['service_location'],
            $data['origin'],
            $data['converted_by'],
            $data['payment_type'],
            $data['entry_value'],
            $data['monthly_value'],
            $data['months'],
            $data['pay_day'],
            $data['contract_start'],
            $data['contract_end'],
            $id,
        ]);

        self::syncServices($id, $services);
    }

    public static function syncServices(int $leadId, array $services): void
    {
        $pdo = Database::getConnection();
        $pdo->prepare('DELETE FROM lead_services WHERE lead_id = ?')->execute([$leadId]);
        $stmt = $pdo->prepare('INSERT INTO lead_services (lead_id, service_code) VALUES (?, ?)');
        foreach ($services as $service) {
            $stmt->execute([$leadId, $service]);
        }
    }
}
