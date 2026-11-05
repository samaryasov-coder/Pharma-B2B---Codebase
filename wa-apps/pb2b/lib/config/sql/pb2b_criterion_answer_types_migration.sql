-- Read-only DESCRIBE on 2026-10-14 confirmed the shared DB lacks all three columns.
-- Review the target schema again before applying; this file is not run automatically.
ALTER TABLE pb2b_tender_criterion
    ADD COLUMN IF NOT EXISTS description TEXT NULL AFTER is_mandatory,
    ADD COLUMN IF NOT EXISTS confirmation_text VARCHAR(255) NULL AFTER description,
    ADD COLUMN IF NOT EXISTS sort INT UNSIGNED NOT NULL DEFAULT 0 AFTER confirmation_text;

UPDATE pb2b_tender_criterion SET type = 'text' WHERE type = 'non_price';
