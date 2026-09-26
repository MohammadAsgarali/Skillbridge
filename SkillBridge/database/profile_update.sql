USE skillbridge;

-- Run this once if your existing SkillBridge database was created from an older schema.
ALTER TABLE students
    ADD COLUMN phone VARCHAR(30) DEFAULT NULL,
    ADD COLUMN course VARCHAR(120) DEFAULT NULL,
    ADD COLUMN graduation_year SMALLINT DEFAULT NULL,
    ADD COLUMN college_location VARCHAR(120) DEFAULT NULL,
    ADD COLUMN linkedin_url VARCHAR(255) DEFAULT NULL,
    ADD COLUMN github_url VARCHAR(255) DEFAULT NULL,
    ADD COLUMN instagram_url VARCHAR(255) DEFAULT NULL,
    ADD COLUMN portfolio_url VARCHAR(255) DEFAULT NULL;
