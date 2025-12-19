INSERT INTO dashboard_settings (id, main_goal_name, main_goal_value, meetings_goal, meetings_done, deals_goal, deals_done, created_at)
VALUES (1, 'Meta Principal', 0, 0, 0, 0, 0, NOW());

INSERT INTO users (name, email, password_hash, role, position, must_change_password, active, created_at)
VALUES ('Administrador', 'admin@dz7marketing.com', SHA2('ChangeMe123!', 256), 'ADMIN', 'Diretoria', 1, 1, NOW());
