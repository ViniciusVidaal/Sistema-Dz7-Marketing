<?php

class DashboardSetting
{
    public static function get(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM dashboard_settings WHERE id = 1');
        $settings = $stmt->fetch();
        if (!$settings) {
            $pdo->exec('INSERT INTO dashboard_settings (id, main_goal_name, main_goal_value, meetings_goal, meetings_done, deals_goal, deals_done, created_at) VALUES (1, "Meta Principal", 0, 0, 0, 0, 0, NOW())');
            $stmt = $pdo->query('SELECT * FROM dashboard_settings WHERE id = 1');
            $settings = $stmt->fetch();
        }
        return $settings;
    }

    public static function update(array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE dashboard_settings SET main_goal_name = ?, main_goal_value = ?, meetings_goal = ?, meetings_done = ?, deals_goal = ?, deals_done = ?, updated_at = NOW() WHERE id = 1');
        $stmt->execute([
            $data['main_goal_name'],
            $data['main_goal_value'],
            $data['meetings_goal'],
            $data['meetings_done'],
            $data['deals_goal'],
            $data['deals_done'],
        ]);
    }
}
