<?php

class RecurringContract
{
    public static function list(array $filters = []): array
    {
        $pdo = Database::getConnection();
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'status = ?';
            $params[] = $filters['status'];
        }

        $sql = 'SELECT rc.*, l.name, l.business_gmn FROM recurring_contracts rc JOIN leads l ON rc.lead_id = l.id';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY rc.created_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT rc.*, l.name, l.business_gmn FROM recurring_contracts rc JOIN leads l ON rc.lead_id = l.id WHERE rc.id = ?');
        $stmt->execute([$id]);
        $contract = $stmt->fetch();
        if (!$contract) {
            return null;
        }
        $contract['payments'] = self::payments($id);
        return $contract;
    }

    public static function findByLeadId(int $leadId): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM recurring_contracts WHERE lead_id = ? LIMIT 1');
        $stmt->execute([$leadId]);
        $contract = $stmt->fetch();
        return $contract ?: null;
    }

    public static function createFromLead(int $leadId, array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO recurring_contracts (lead_id, entry_value, monthly_value, months, pay_day, start_date, end_date, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, "ACTIVE", NOW())');
        $stmt->execute([
            $leadId,
            $data['entry_value'],
            $data['monthly_value'],
            $data['months'],
            $data['pay_day'],
            $data['contract_start'],
            $data['contract_end'],
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function updateFromLead(int $leadId, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE recurring_contracts SET entry_value = ?, monthly_value = ?, months = ?, pay_day = ?, start_date = ?, end_date = ?, status = \"ACTIVE\", updated_at = NOW() WHERE lead_id = ?');
        $stmt->execute([
            $data['entry_value'],
            $data['monthly_value'],
            $data['months'],
            $data['pay_day'],
            $data['contract_start'],
            $data['contract_end'],
            $leadId,
        ]);
    }

    public static function updateStatus(int $id, string $status): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE recurring_contracts SET status = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$status, $id]);
    }

    public static function payments(int $contractId): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM recurring_payments WHERE recurring_contract_id = ? ORDER BY reference_month DESC');
        $stmt->execute([$contractId]);
        return $stmt->fetchAll();
    }

    public static function getMonthlyStatus(int $contractId, string $month): string
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT status FROM recurring_payments WHERE recurring_contract_id = ? AND reference_month = ? LIMIT 1');
        $stmt->execute([$contractId, $month]);
        $status = $stmt->fetchColumn();
        return $status ?: 'PENDING';
    }

    public static function markPayment(int $contractId, string $month, string $status, ?string $paidAt = null): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id FROM recurring_payments WHERE recurring_contract_id = ? AND reference_month = ?');
        $stmt->execute([$contractId, $month]);
        $existingId = $stmt->fetchColumn();

        if ($existingId) {
            $update = $pdo->prepare('UPDATE recurring_payments SET status = ?, paid_at = ?, updated_at = NOW() WHERE id = ?');
            $update->execute([$status, $paidAt, $existingId]);
        } else {
            $insert = $pdo->prepare('INSERT INTO recurring_payments (recurring_contract_id, reference_month, status, paid_at, created_at) VALUES (?, ?, ?, ?, NOW())');
            $insert->execute([$contractId, $month, $status, $paidAt]);
        }
    }
}
