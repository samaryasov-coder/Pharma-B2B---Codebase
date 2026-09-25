-- Лоты / позиции и документы тендера (ЗЦ Wave lots)
-- Вставить в Workbench на БД test-ivan. Повторный запуск безопасен (IF NOT EXISTS).
-- file_link_id → pb2b_file_links.id (как в docflow); FK не ставим — как у остальных wave-таблиц.

CREATE TABLE IF NOT EXISTS pb2b_tender_item (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    qty DECIMAL(18,3) NOT NULL DEFAULT 1,
    unit VARCHAR(32) NULL DEFAULT NULL,
    max_price_no_vat DECIMAL(18,2) NULL DEFAULT NULL,
    vat_rate VARCHAR(16) NULL DEFAULT NULL COMMENT 'например 0%, 10%, 20%',
    delivery_place VARCHAR(255) NULL DEFAULT NULL,
    comment TEXT NULL,
    file_link_id INT UNSIGNED NULL DEFAULT NULL,
    sort INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY ix_tender_id (tender_id),
    KEY ix_file_link_id (file_link_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pb2b_tender_document (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    kind VARCHAR(32) NOT NULL COMMENT 'tech_spec | requirement',
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_required TINYINT UNSIGNED NOT NULL DEFAULT 0,
    file_link_id INT UNSIGNED NULL DEFAULT NULL,
    sort INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY ix_tender_id (tender_id),
    KEY ix_tender_kind (tender_id, kind),
    KEY ix_file_link_id (file_link_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
