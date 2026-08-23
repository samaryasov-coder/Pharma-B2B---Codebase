CREATE TABLE IF NOT EXISTS pb2b_criterion (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    type VARCHAR(32) NOT NULL DEFAULT 'non_price',
    name VARCHAR(255) NOT NULL,
    weight DECIMAL(8,2) NULL,
    is_mandatory TINYINT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY ix_tender_id (tender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
