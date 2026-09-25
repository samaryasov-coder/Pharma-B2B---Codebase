-- Заявка участия ЗЦ (Wave 2). Цены поставщика — только здесь, не в pb2b_tender.budget.
-- Вставить в Workbench на БД test-ivan. Повторный запуск безопасен (IF NOT EXISTS).
-- Статусы — строковые code из config tender_application_* (не tender_statuses).
-- file_link_id → pb2b_file_links.id; FK не ставим — как у lots-wave.

CREATE TABLE IF NOT EXISTS pb2b_application (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    supplier_company_id INT UNSIGNED NOT NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'draft' COMMENT 'draft | submitted | withdrawn',
    nonprice_done TINYINT UNSIGNED NOT NULL DEFAULT 0,
    approval_status VARCHAR(32) NOT NULL DEFAULT 'pending' COMMENT 'pending | approved | rejected | not_required',
    qualification_status VARCHAR(32) NOT NULL DEFAULT 'pending' COMMENT 'pending | passed | failed | not_required',
    admission_status VARCHAR(32) NOT NULL DEFAULT 'pending' COMMENT 'pending | admitted | rejected',
    approval_comment TEXT NULL,
    qualification_comment TEXT NULL,
    admission_comment TEXT NULL,
    submitted_at DATETIME NULL DEFAULT NULL,
    withdrawn_at DATETIME NULL DEFAULT NULL,
    create_datetime DATETIME NULL DEFAULT NULL,
    update_datetime DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY ux_tender_supplier (tender_id, supplier_company_id),
    KEY ix_supplier_company_id (supplier_company_id),
    KEY ix_tender_status (tender_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pb2b_application_item (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    application_id INT UNSIGNED NOT NULL,
    tender_item_id INT UNSIGNED NOT NULL,
    qty DECIMAL(18,3) NULL DEFAULT NULL,
    unit VARCHAR(32) NULL DEFAULT NULL,
    price_per_unit DECIMAL(18,2) NULL DEFAULT NULL,
    vat_rate VARCHAR(16) NULL DEFAULT NULL COMMENT 'например 0%, 10%, 20%',
    sort INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY ux_application_item (application_id, tender_item_id),
    KEY ix_tender_item_id (tender_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pb2b_application_document (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    application_id INT UNSIGNED NOT NULL,
    tender_document_id INT UNSIGNED NULL DEFAULT NULL COMMENT 'requirement извещения, если есть',
    name VARCHAR(255) NOT NULL,
    comment TEXT NULL,
    file_link_id INT UNSIGNED NULL DEFAULT NULL,
    sort INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY ix_application_id (application_id),
    KEY ix_tender_document_id (tender_document_id),
    KEY ix_file_link_id (file_link_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pb2b_application_criterion (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    application_id INT UNSIGNED NOT NULL,
    criterion_id INT UNSIGNED NOT NULL,
    value TEXT NULL,
    file_link_id INT UNSIGNED NULL DEFAULT NULL,
    confirmed TINYINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY ux_application_criterion (application_id, criterion_id),
    KEY ix_criterion_id (criterion_id),
    KEY ix_file_link_id (file_link_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
