CREATE TABLE IF NOT EXISTS pb2b_invitation (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    supplier_company_id INT UNSIGNED NOT NULL,
    invited_by_contact_id INT UNSIGNED NULL,
    status VARCHAR(32) NOT NULL DEFAULT 'invited',
    sent_at DATETIME NULL,
    responded_at DATETIME NULL,
    PRIMARY KEY (id),
    KEY ix_tender_id (tender_id),
    KEY ix_supplier_company_id (supplier_company_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
