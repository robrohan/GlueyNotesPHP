ALTER TABLE session DROP KEY idx_userid;
ALTER TABLE session ADD INDEX idx_userid (user_id);