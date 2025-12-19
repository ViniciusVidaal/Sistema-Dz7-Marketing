CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    position VARCHAR(120),
    must_change_password TINYINT(1) DEFAULT 1,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME,
    CHECK (role IN ('ADMIN','EMPLOYEE'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE password_resets_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    created_at DATETIME,
    resolved_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CHECK (status IN ('PENDING','DONE'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    phone VARCHAR(50),
    email VARCHAR(120),
    contact_name VARCHAR(120),
    business_gmn VARCHAR(120),
    city VARCHAR(120),
    service_location VARCHAR(120),
    origin VARCHAR(40) NOT NULL,
    converted_by INT,
    payment_type VARCHAR(20) NOT NULL,
    entry_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    monthly_value DECIMAL(12,2) DEFAULT 0,
    months INT DEFAULT 0,
    pay_day INT DEFAULT 1,
    contract_start DATE,
    contract_end DATE,
    created_by INT,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (converted_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CHECK (origin IN ('META_ADS','GOOGLE_ADS','INDICACAO','PAP','TERCEIRIZADO','OUTROS')),
    CHECK (payment_type IN ('ONE_TIME','RECURRING'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_leads_name ON leads(name);
CREATE INDEX idx_leads_gmn ON leads(business_gmn);

CREATE TABLE lead_services (
    lead_id INT NOT NULL,
    service_code VARCHAR(40) NOT NULL,
    PRIMARY KEY (lead_id, service_code),
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE recurring_contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lead_id INT NOT NULL,
    entry_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    monthly_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    months INT DEFAULT 0,
    pay_day INT DEFAULT 1,
    start_date DATE,
    end_date DATE,
    status VARCHAR(20) NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
    CHECK (status IN ('ACTIVE','INACTIVE'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_recurring_status ON recurring_contracts(status);
CREATE INDEX idx_recurring_payday ON recurring_contracts(pay_day);

CREATE TABLE recurring_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recurring_contract_id INT NOT NULL,
    reference_month VARCHAR(7) NOT NULL,
    status VARCHAR(20) NOT NULL,
    paid_at DATE,
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE KEY uniq_recurring_month (recurring_contract_id, reference_month),
    FOREIGN KEY (recurring_contract_id) REFERENCES recurring_contracts(id) ON DELETE CASCADE,
    CHECK (status IN ('PAID','PENDING','INADIMPLENT'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE financial_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_date DATE NOT NULL,
    direction VARCHAR(10) NOT NULL,
    type VARCHAR(40) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    description VARCHAR(255),
    details TEXT,
    related_entity_type VARCHAR(40),
    related_entity_id INT,
    created_by INT,
    created_at DATETIME,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CHECK (direction IN ('IN','OUT')),
    CHECK (type IN ('CONTRACT_ENTRY','RECURRING_PAYMENT','TRAFFIC_SPEND','MISC_SPEND','CASH_ADJUSTMENT'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_financial_date ON financial_events(event_date);
CREATE INDEX idx_financial_type ON financial_events(type);
CREATE INDEX idx_financial_direction ON financial_events(direction);
CREATE INDEX idx_financial_date_type_dir ON financial_events(event_date, type, direction);

CREATE TABLE tools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    value DECIMAL(12,2) NOT NULL DEFAULT 0,
    start_date DATE,
    next_due_date DATE,
    card_last4 VARCHAR(4),
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE investments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT,
    value DECIMAL(12,2) NOT NULL DEFAULT 0,
    priority VARCHAR(20) NOT NULL,
    due_date DATE,
    status VARCHAR(20) NOT NULL,
    created_by INT,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CHECK (priority IN ('ALTA','MEDIA','BAIXA'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE goals_company (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    target_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    current_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    start_date DATE,
    end_date DATE,
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE goals_personal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    type VARCHAR(20) NOT NULL,
    target_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    current_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    start_date DATE,
    end_date DATE,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CHECK (type IN ('DAILY','MONTHLY','YEARLY','CUSTOM'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE dashboard_settings (
    id INT PRIMARY KEY,
    main_goal_name VARCHAR(120) NOT NULL,
    main_goal_value DECIMAL(12,2) NOT NULL DEFAULT 0,
    meetings_goal INT NOT NULL DEFAULT 0,
    meetings_done INT NOT NULL DEFAULT 0,
    deals_goal INT NOT NULL DEFAULT 0,
    deals_done INT NOT NULL DEFAULT 0,
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE approvals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_type VARCHAR(40) NOT NULL,
    entity_id INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    requested_by INT NOT NULL,
    requested_at DATETIME,
    approved_by INT,
    approved_at DATETIME,
    summary VARCHAR(255),
    before_json LONGTEXT,
    after_json LONGTEXT,
    FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    CHECK (status IN ('PENDING','APPROVED','REJECTED','EXPIRED'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_approvals_status ON approvals(status);
CREATE INDEX idx_approvals_entity ON approvals(entity_type);
CREATE INDEX idx_approvals_status_entity ON approvals(status, entity_type);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(40) NOT NULL,
    title VARCHAR(160) NOT NULL,
    body VARCHAR(255),
    reference_type VARCHAR(40),
    reference_id INT,
    notify_date DATE,
    unique_key VARCHAR(120) NOT NULL,
    created_at DATETIME,
    UNIQUE KEY uniq_notifications_key (unique_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_notifications_created ON notifications(created_at);

CREATE TABLE notification_reads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT NOT NULL,
    user_id INT NOT NULL,
    read_at DATETIME,
    UNIQUE KEY uniq_notification_user (notification_id, user_id),
    FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
