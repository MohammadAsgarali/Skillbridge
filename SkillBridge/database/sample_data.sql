-- ============================================
-- Sample data so the demo has something to show
-- Member 1 (Junaid) -- run AFTER schema.sql
-- ============================================
USE skillbridge;

-- Default admin login -> username: admin | password: admin123
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- If that hash ever fails to login, regenerate with:
-- php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
-- and UPDATE admins SET password='<new hash>' WHERE username='admin';

INSERT INTO skills (skill_name, category) VALUES
('HTML', 'Frontend'), ('CSS', 'Frontend'), ('JavaScript', 'Frontend'),
('PHP', 'Backend'), ('MySQL', 'Backend'), ('Python', 'Backend'),
('React', 'Frontend'), ('Node.js', 'Backend'), ('Java', 'Backend'),
('Bootstrap', 'Frontend'), ('Git', 'Tools'), ('C++', 'Programming'),
('Data Structures', 'Core CS'), ('Machine Learning', 'AI/ML'), ('UI/UX Design', 'Design');

INSERT INTO opportunities (title, company, type, description, location, duration, stipend, posted_by) VALUES
('Full Stack Web Developer Intern', 'TechNova Solutions', 'Internship',
 'Work on PHP/MySQL based web applications, build REST APIs and responsive UI.',
 'Remote', '3 Months', '10000', 1),
('Frontend Developer Project', 'PixelCraft Studio', 'Project',
 'Build a responsive dashboard using HTML, CSS, JavaScript and Bootstrap.',
 'Ghaziabad', '1 Month', 'Unpaid', 1),
('Machine Learning Intern', 'DataWise Labs', 'Internship',
 'Assist in building ML models using Python and analyzing datasets.',
 'Remote', '2 Months', '8000', 1),
('Backend Developer Intern', 'CloudEdge Systems', 'Internship',
 'Design and maintain PHP/MySQL backend services and APIs.',
 'Noida', '3 Months', '12000', 1);

-- Map required skills to each opportunity (ids follow insert order above: 1-4)
INSERT INTO opportunity_skills (opportunity_id, skill_id, required_level) VALUES
(1, 4, 'Intermediate'), (1, 5, 'Intermediate'), (1, 1, 'Beginner'), (1, 3, 'Intermediate'),
(2, 1, 'Intermediate'), (2, 2, 'Intermediate'), (2, 3, 'Intermediate'), (2, 10, 'Beginner'),
(3, 6, 'Intermediate'), (3, 14, 'Beginner'), (3, 13, 'Beginner'),
(4, 4, 'Advanced'), (4, 5, 'Intermediate'), (4, 11, 'Beginner');
