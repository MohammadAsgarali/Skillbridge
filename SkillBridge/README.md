# SkillBridge
Skill-Based Internship & Project Matching System — built for WebCraft24 (GLBIM TECH VISOR CLUB Hackathon).

**Team HACK-242:** Mohd Junaid Khan · Mohhamad Asgar Ali · Mohd Mohtashim Ansari · Kunal Anand

## Tech Stack
HTML, CSS, Bootstrap, JavaScript, PHP, MySQL (XAMPP)

## Setup
1. Copy this whole `SkillBridge` folder into `C:\xampp\htdocs\`
2. Start Apache + MySQL in the XAMPP control panel. This project is configured for **MySQL port 3307**.
3. For a new installation, open `http://localhost/phpmyadmin` and import:
   - `database/schema.sql` first
   - `database/sample_data.sql` second
4. Visit `http://localhost/SkillBridge/index.php`
5. If you already have the older SkillBridge database, **do not delete it**. The application automatically adds the missing `course`, `career_domain` and profile columns when it connects.
6. Admin panel: `http://localhost/SkillBridge/admin/login.php` (admin / admin123)

## Team ownership (for this repo)
| Area | Owner | Files |
|---|---|---|
| Database + Auth + Core Config | Junaid | `database/`, `config/`, `includes/auth.php`, `includes/admin_auth.php`, `includes/functions.php`, `register.php`, `login.php`, `logout.php` |
| Frontend Shell | Asgar | `includes/header.php`, `includes/navbar.php`, `includes/footer.php`, `index.php`, `assets/css/`, `assets/js/script.js` |
| Student Module + Matching Engine | Mohtashim | `dashboard.php`, `profile.php`, `skills.php`, `opportunities.php`, `opportunity.php`, `recommendations.php`, `applications.php`, `bookmarks.php`, `matching/` |
| Admin Module + API | Kunal | `admin/`, `api/` |


## New Module: Career Roadmap & Course Recommendations

The project now includes `career_roadmap.php`.

### What it does
- Supports multiple academic backgrounds including B.Tech, M.Tech, BCA, MCA, BBA, MBA, B.Com, M.Com, B.Sc, M.Sc, BA, MA and B.Des.
- Calculates an explainable career compatibility percentage.
- Formula: 40% degree fit + 50% current skill fit + 10% profile readiness.
- Suggests skills to learn, courses to take, portfolio projects to build and learning resources.
- Includes career tracks for software/web development, data/BI, business analysis, finance/FinTech, digital marketing, HR analytics, AI/ML, cybersecurity, cloud/DevOps, UI/UX, product management, accounting/business operations, e-commerce and networking/IT support.
- Adds a `Career Roadmap` item to the student sidebar and dashboard.
- Adds `api/career_recommendations.php` for JSON access to the recommendation engine.

### Setup
No new database table is required. The module uses the existing `students.course`, `students.college` and `student_skills` data. After replacing the project files, log in, open **Profile → College & Education**, select the course/degree, add skills, and open **Career Roadmap**.

## Domain-first Career Roadmap
After student login, SkillBridge asks for **Course + Career Domain**. The selected domain drives a step-by-step skills roadmap. Students can add each learned skill from the roadmap, after which the compatibility percentage is recalculated. The module also shows domain-specific courses, projects, learning resources and current internship/project matches.
