ALTER TABLE pb2b_prequal_document
    ADD COLUMN kind VARCHAR(32) NOT NULL DEFAULT 'requirement' AFTER prequal_id,
    ADD COLUMN file_link_id INT NULL AFTER description,
    ADD COLUMN is_required TINYINT NOT NULL DEFAULT 0 AFTER file_link_id,
    ADD KEY ix_prequal_kind (prequal_id, kind),
    ADD KEY ix_file_link (file_link_id);

INSERT INTO pb2b_prequal_document (prequal_id, kind, name, description, file_link_id, is_required, sort, create_datetime, update_datetime)
SELECT a.prequal_id, 'tech_spec', COALESCE(fl.filename, 'Документация ПК'), '', a.file_link_id, 0, a.sort, a.create_datetime, a.update_datetime
FROM pb2b_prequal_attachment a
LEFT JOIN pb2b_file_links fl ON fl.id = a.file_link_id
WHERE NOT EXISTS (
    SELECT 1 FROM pb2b_prequal_document d
    WHERE d.prequal_id = a.prequal_id AND d.kind = 'tech_spec' AND d.file_link_id = a.file_link_id
);

ALTER TABLE pb2b_prequal_document DROP COLUMN file_set_id;
DROP TABLE pb2b_prequal_attachment;
