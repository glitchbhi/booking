ALTER TABLE grounds ADD COLUMN opening_time TIME NOT NULL DEFAULT '06:00:00' AFTER night_rate_end;
ALTER TABLE grounds ADD COLUMN closing_time TIME NOT NULL DEFAULT '22:00:00' AFTER opening_time;
ALTER TABLE grounds ADD COLUMN slot_duration INT UNSIGNED NOT NULL DEFAULT 60 COMMENT 'Duration of each booking slot in minutes' AFTER closing_time;
