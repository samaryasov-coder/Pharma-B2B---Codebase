CREATE TABLE IF NOT EXISTS pb2b_tender_classifier (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    classifier_type INT UNSIGNED NOT NULL,
    classifier_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tender_classifier (tender_id, classifier_type, classifier_id),
    KEY ix_tender_id (tender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
