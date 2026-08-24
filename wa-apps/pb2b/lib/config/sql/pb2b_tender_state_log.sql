CREATE TABLE IF NOT EXISTS pb2b_tender_state_log (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    from_status INT UNSIGNED NULL,
    to_status INT UNSIGNED NOT NULL,
    actor_contact_id INT UNSIGNED NULL,
    reason TEXT NULL,
    at_dt DATETIME NOT NULL,
    PRIMARY KEY (id),
    KEY ix_tender_id (tender_id),
    KEY ix_at_dt (at_dt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
