-- Миграция Wave 1 для мастера ЗЦ (price_request)
-- Запускать на сервере test-ivan / phpMyAdmin / SSH-туннель к MySQL.
-- Локально: mysql ... < pb2b_tender_zc_wave1_migration.sql
--
-- Перед запуском: SHOW CREATE TABLE pb2b_tender; — таблица должна уже существовать.
-- Повторный запуск: ALTER ADD COLUMN упадёт, если колонка есть — пропустите блок или выполняйте по частям.

-- ---------------------------------------------------------------------------
-- 1. Дочерние таблицы (если ещё нет)
-- ---------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS pb2b_tender_classifier (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    classifier_type INT UNSIGNED NOT NULL,
    classifier_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tender_classifier (tender_id, classifier_type, classifier_id),
    KEY ix_tender_id (tender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pb2b_criterion (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tender_id INT UNSIGNED NOT NULL,
    type VARCHAR(32) NOT NULL DEFAULT 'non_price',
    name VARCHAR(255) NOT NULL,
    weight DECIMAL(8,2) NULL,
    is_mandatory TINYINT UNSIGNED NOT NULL DEFAULT 0,
    description TEXT NULL,
    PRIMARY KEY (id),
    KEY ix_tender_id (tender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- description для criterion, если таблица создана раньше без него
-- ALTER TABLE pb2b_criterion ADD COLUMN description TEXT NULL AFTER is_mandatory;

-- ---------------------------------------------------------------------------
-- 2. pb2b_tender — колонки P1 для полного мастера ЗЦ (visibility + params)
--    Выполнять только те ALTER, которых нет в SHOW COLUMNS.
-- ---------------------------------------------------------------------------

ALTER TABLE pb2b_tender
    ADD COLUMN hide_initial_price TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Скрывать начальную цену',
    ADD COLUMN hide_participants_count TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Скрывать число участников',
    ADD COLUMN hide_participant_prices TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Скрывать цены участников',
    ADD COLUMN rank_prices_mode VARCHAR(16) NULL DEFAULT NULL COMMENT 'use|dont_use — ранги по ценам',
    ADD COLUMN organizer_sees_names TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Имена участников видны организатору',
    ADD COLUMN docs_end_at DATETIME NULL DEFAULT NULL COMMENT 'Окончание загрузки документов',
    ADD COLUMN allow_analogues TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Разрешены аналоги',
    ADD COLUMN vat_mode VARCHAR(16) NOT NULL DEFAULT 'with_vat' COMMENT 'with_vat|without_vat',
    ADD COLUMN auto_extend_no_offers TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN auto_extend_on_change TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN auto_extend_period_min INT UNSIGNED NULL DEFAULT NULL,
    ADD COLUMN min_step_enabled TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN min_step_base VARCHAR(8) NULL DEFAULT NULL COMMENT 'own|best',
    ADD COLUMN min_step_type VARCHAR(8) NULL DEFAULT NULL COMMENT 'amount|percent',
    ADD COLUMN min_step_value DECIMAL(18,2) NULL DEFAULT NULL,
    ADD COLUMN only_price_reduction TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN require_additional_docs TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN additional_info TEXT NULL,
    ADD COLUMN additional_delivery_info TEXT NULL,
    ADD COLUMN past_prequal_tender_id INT UNSIGNED NULL DEFAULT NULL,
    ADD COLUMN published_at DATETIME NULL DEFAULT NULL;

-- Опционально: отделить «итоги» от opening_at (если нужна семантика layout)
-- ALTER TABLE pb2b_tender ADD COLUMN results_planned_at DATETIME NULL DEFAULT NULL AFTER opening_at;
