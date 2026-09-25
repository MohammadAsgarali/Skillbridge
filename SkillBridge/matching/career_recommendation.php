<?php
/**
 * SkillBridge Career Roadmap & Course Recommendation Engine
 *
 * Rule-based engine:
 * - Academic background fit: 40%
 * - Current skill fit: 50%
 * - Profile readiness: 10%
 *
 * It does NOT claim to be an AI prediction. The score is transparent and
 * explainable so students can see exactly what they should learn next.
 */

function sbNormalizeText($value) {
    $value = strtolower(trim((string)$value));
    $value = preg_replace('/[^a-z0-9+#.\- ]+/i', ' ', $value);
    return preg_replace('/\s+/', ' ', $value);
}

function sbDegreeFamily($course) {
    $c = sbNormalizeText($course);
    if ($c === '') return 'unknown';
    if (preg_match('/\bm\.?tech\b|\bmtech\b|\bb\.?tech\b|\bbtech\b|\bbe\b|\bme\b/', $c)) return 'engineering';
    if (preg_match('/\bmca\b|\bbca\b/', $c)) return 'computer';
    if (preg_match('/\bmba\b|\bbba\b|\bpgdm\b/', $c)) return 'business';
    if (preg_match('/\bb\.?com\b|\bm\.?com\b|\bbcom\b|\bmcom\b|\bca\b|\bcma\b/', $c)) return 'commerce';
    if (preg_match('/\bbsc\b|\bm\.?sc\b|\bmsc\b/', $c)) return 'science';
    if (preg_match('/\bba\b|\bma\b/', $c)) return 'arts';
    if (preg_match('/\bbdes\b|\bdesign\b/', $c)) return 'design';
    return 'other';
}

function sbCourseMatches($course, $degrees) {
    $family = sbDegreeFamily($course);
    if ($family === 'unknown') return 30;
    foreach ($degrees as $d) {
        if ($family === $d) return 100;
    }
    return 45; // cross-discipline path: still possible, but needs more upskilling
}

function sbResource($name, $url, $type = 'Learning') {
    return ['name' => $name, 'url' => $url, 'type' => $type];
}

/**
 * Career catalog. Add more paths here without changing the recommendation UI.
 */
function sbCareerCatalog() {
    return [
        [
            'id' => 'software-web',
            'title' => 'Software & Web Development',
            'icon' => 'bi-code-slash',
            'degrees' => ['computer','engineering','science'],
            'skills' => ['HTML','CSS','JavaScript','Git','MySQL','PHP'],
            'courses' => [
                'Full-Stack Web Development',
                'JavaScript & REST API Development',
                'SQL and Database Fundamentals'
            ],
            'projects' => [
                'Build a full-stack student placement portal',
                'Build a REST API + admin dashboard for a college',
                'Build an e-commerce website with login and MySQL'
            ],
            'resources' => [
                sbResource('freeCodeCamp – Web Development', 'https://www.freecodecamp.org/learn/'),
                sbResource('MDN Web Docs – HTML/CSS/JavaScript', 'https://developer.mozilla.org/en-US/docs/Learn'),
                sbResource('GitHub Skills – Git & GitHub', 'https://skills.github.com/')
            ],
            'description' => 'A practical software path for students who enjoy coding websites, APIs and database applications.'
        ],
        [
            'id' => 'data-analyst',
            'title' => 'Data Analyst / BI Analyst',
            'icon' => 'bi-bar-chart-line',
            'degrees' => ['business','commerce','computer','science','engineering'],
            'skills' => ['Excel','SQL','Power BI','Python','Statistics','Data Visualization'],
            'courses' => [
                'Advanced Excel for Business Analytics',
                'SQL for Data Analysis',
                'Power BI / Business Intelligence',
                'Python for Data Analysis'
            ],
            'projects' => [
                'Sales dashboard using Excel + Power BI',
                'Customer churn analysis using Python',
                'College placement analytics dashboard'
            ],
            'resources' => [
                sbResource('Microsoft Learn – Power BI', 'https://learn.microsoft.com/training/powerplatform/power-bi/'),
                sbResource('Kaggle Learn – Data & Python', 'https://www.kaggle.com/learn'),
                sbResource('Microsoft Learn – Data Analytics', 'https://learn.microsoft.com/training/browse/?products=power-bi')
            ],
            'description' => 'Turn business data into dashboards, reports and useful decisions. Especially relevant for B.Com, BBA, MBA, BCA and analytics-focused students.'
        ],
        [
            'id' => 'business-analyst',
            'title' => 'Business Analyst',
            'icon' => 'bi-briefcase',
            'degrees' => ['business','commerce','computer','engineering'],
            'skills' => ['Excel','SQL','Power BI','Statistics','Data Visualization','Communication'],
            'courses' => [
                'Business Analysis Fundamentals',
                'Advanced Excel + Power BI',
                'SQL for Business Analysts',
                'Requirements Gathering & Documentation'
            ],
            'projects' => [
                'Analyze sales performance and write a business report',
                'Create a Power BI KPI dashboard for a retail company',
                'Prepare requirements and user stories for a college app'
            ],
            'resources' => [
                sbResource('Microsoft Learn – Power BI', 'https://learn.microsoft.com/training/powerplatform/power-bi/'),
                sbResource('IBM SkillsBuild – Business & Tech Skills', 'https://skillsbuild.org/'),
                sbResource('Coursera – Business Analysis', 'https://www.coursera.org/browse/business/business-strategy')
            ],
            'description' => 'A bridge between business teams and technology teams: requirements, data, reports and process improvement.'
        ],
        [
            'id' => 'finance-fintech',
            'title' => 'Finance / FinTech Analyst',
            'icon' => 'bi-currency-rupee',
            'degrees' => ['commerce','business','science','computer'],
            'skills' => ['Excel','Financial Analysis','SQL','Power BI','Statistics','Communication'],
            'courses' => [
                'Financial Modelling in Excel',
                'Business & Financial Analytics',
                'SQL for Finance',
                'Power BI for Finance'
            ],
            'projects' => [
                'Build a personal finance tracker and dashboard',
                'Create a company financial analysis report',
                'Build a loan / EMI analysis dashboard'
            ],
            'resources' => [
                sbResource('Microsoft Learn – Power BI', 'https://learn.microsoft.com/training/powerplatform/power-bi/'),
                sbResource('Khan Academy – Finance & Capital Markets', 'https://www.khanacademy.org/economics-finance-domain/core-finance'),
                sbResource('NPTEL – Finance & Management Courses', 'https://nptel.ac.in/courses')
            ],
            'description' => 'Use finance knowledge with analytics and technology. Strong fit for commerce and business students who want FinTech roles.'
        ],
        [
            'id' => 'digital-marketing',
            'title' => 'Digital Marketing & Growth',
            'icon' => 'bi-megaphone',
            'degrees' => ['business','commerce','arts','computer'],
            'skills' => ['Digital Marketing','SEO','Content Writing','Google Analytics','Communication','Social Media'],
            'courses' => [
                'Digital Marketing Fundamentals',
                'SEO & Content Marketing',
                'Google Analytics / Measurement',
                'Performance Marketing Basics'
            ],
            'projects' => [
                'Create a complete digital marketing plan for a local business',
                'Build an SEO audit + keyword research report',
                'Run a mock social-media campaign and measure results'
            ],
            'resources' => [
                sbResource('Google Skillshop – Analytics & Ads', 'https://skillshop.withgoogle.com/'),
                sbResource('HubSpot Academy – Marketing', 'https://academy.hubspot.com/'),
                sbResource('Meta Blueprint – Digital Marketing', 'https://www.facebook.com/business/learn')
            ],
            'description' => 'Ideal for students interested in marketing, branding, content, SEO, social media and measurable business growth.'
        ],
        [
            'id' => 'hr-analytics',
            'title' => 'HR / People Analytics',
            'icon' => 'bi-people',
            'degrees' => ['business','commerce','arts','science'],
            'skills' => ['Excel','Power BI','Communication','Statistics','Data Visualization','HR Analytics'],
            'courses' => [
                'Human Resource Management',
                'HR Analytics with Excel / Power BI',
                'People Metrics & Dashboarding'
            ],
            'projects' => [
                'Employee attrition dashboard',
                'Recruitment funnel analytics project',
                'Employee engagement survey + analysis'
            ],
            'resources' => [
                sbResource('Microsoft Learn – Power BI', 'https://learn.microsoft.com/training/powerplatform/power-bi/'),
                sbResource('IBM SkillsBuild – Professional Skills', 'https://skillsbuild.org/'),
                sbResource('Coursera – Human Resources', 'https://www.coursera.org/browse/business/human-resources')
            ],
            'description' => 'Combine people management with data to understand hiring, retention, engagement and workforce trends.'
        ],
        [
            'id' => 'ai-ml',
            'title' => 'AI / Machine Learning',
            'icon' => 'bi-robot',
            'degrees' => ['engineering','computer','science'],
            'skills' => ['Python','Statistics','Machine Learning','Data Structures','SQL','Data Visualization'],
            'courses' => [
                'Python for Data Science',
                'Statistics & Probability for ML',
                'Machine Learning Fundamentals',
                'Deep Learning / Generative AI Basics'
            ],
            'projects' => [
                'Student placement prediction system',
                'House-price prediction using ML',
                'Resume skill extraction / job recommendation prototype'
            ],
            'resources' => [
                sbResource('Kaggle Learn – Python & Machine Learning', 'https://www.kaggle.com/learn'),
                sbResource('Google Machine Learning Crash Course', 'https://developers.google.com/machine-learning/crash-course'),
                sbResource('NPTEL – AI / ML Courses', 'https://nptel.ac.in/courses')
            ],
            'description' => 'A technical path for students who want to build predictive models, intelligent applications and AI systems.'
        ],
        [
            'id' => 'cybersecurity',
            'title' => 'Cybersecurity',
            'icon' => 'bi-shield-lock',
            'degrees' => ['computer','engineering','science'],
            'skills' => ['Networking','Linux','Python','Cybersecurity','Git','SQL'],
            'courses' => [
                'Computer Networking',
                'Linux Fundamentals',
                'Cybersecurity Fundamentals',
                'Web Application Security'
            ],
            'projects' => [
                'Build a secure login system with audit logs',
                'Create a network monitoring / log analysis dashboard',
                'Perform a security checklist and threat model for a demo web app'
            ],
            'resources' => [
                sbResource('Cisco Networking Academy', 'https://www.netacad.com/'),
                sbResource('Microsoft Learn – Security', 'https://learn.microsoft.com/training/browse/?subjects=security'),
                sbResource('OWASP Web Security', 'https://owasp.org/www-project-top-ten/')
            ],
            'description' => 'Learn how networks, systems and web applications are protected from common security threats.'
        ],
        [
            'id' => 'cloud-devops',
            'title' => 'Cloud & DevOps',
            'icon' => 'bi-cloud-arrow-up',
            'degrees' => ['computer','engineering','science'],
            'skills' => ['Linux','Git','Docker','Cloud Computing','Python','Networking'],
            'courses' => [
                'Linux & Command Line',
                'Cloud Fundamentals',
                'Docker & Containers',
                'CI/CD and DevOps Fundamentals'
            ],
            'projects' => [
                'Deploy a PHP/MySQL application on a cloud VM',
                'Dockerize a full-stack application',
                'Build a GitHub Actions CI/CD pipeline'
            ],
            'resources' => [
                sbResource('AWS Skill Builder', 'https://skillbuilder.aws/'),
                sbResource('Microsoft Learn – Azure', 'https://learn.microsoft.com/training/azure/'),
                sbResource('Docker Get Started', 'https://docs.docker.com/get-started/')
            ],
            'description' => 'Build, deploy and automate applications using cloud platforms, Linux, containers and CI/CD.'
        ],
        [
            'id' => 'uiux',
            'title' => 'UI/UX Design',
            'icon' => 'bi-palette',
            'degrees' => ['design','arts','business','computer'],
            'skills' => ['UI/UX Design','Figma','Communication','HTML','CSS','User Research'],
            'courses' => [
                'UI/UX Design Fundamentals',
                'Figma for Product Design',
                'User Research & Usability Testing'
            ],
            'projects' => [
                'Redesign a college website in Figma',
                'Design a complete internship/job portal UI',
                'Conduct usability testing and document improvements'
            ],
            'resources' => [
                sbResource('Figma Learn', 'https://help.figma.com/hc/en-us/categories/360002051613-Get-started'),
                sbResource('Google UX Design – Coursera', 'https://www.coursera.org/professional-certificates/google-ux-design'),
                sbResource('freeCodeCamp – Responsive Web Design', 'https://www.freecodecamp.org/learn/2022/responsive-web-design/')
            ],
            'description' => 'For students who enjoy solving user problems through research, wireframes, prototypes and visual design.'
        ],
        [
            'id' => 'product-management',
            'title' => 'Product Management',
            'icon' => 'bi-kanban',
            'degrees' => ['business','computer','engineering','commerce'],
            'skills' => ['Communication','Data Visualization','SQL','UI/UX Design','Project Management','Excel'],
            'courses' => [
                'Product Management Fundamentals',
                'Agile / Scrum',
                'Product Analytics',
                'User Research & Product Discovery'
            ],
            'projects' => [
                'Create a product requirement document (PRD) for a student app',
                'Build a product roadmap and KPI dashboard',
                'Conduct user interviews and propose an MVP'
            ],
            'resources' => [
                sbResource('Atlassian University – Agile & Jira', 'https://university.atlassian.com/'),
                sbResource('Google UX Design Resources', 'https://www.coursera.org/professional-certificates/google-ux-design'),
                sbResource('IBM SkillsBuild', 'https://skillsbuild.org/')
            ],
            'description' => 'Combine business, users, technology and data to plan and improve digital products.'
        ],
        [
            'id' => 'accounting',
            'title' => 'Accounting & Business Operations',
            'icon' => 'bi-journal-check',
            'degrees' => ['commerce','business'],
            'skills' => ['Accounting','Excel','Financial Analysis','Communication','Power BI','Tally'],
            'courses' => [
                'Advanced Excel for Accounting',
                'Accounting & Financial Statements',
                'Tally / ERP Fundamentals',
                'Power BI for Business Reporting'
            ],
            'projects' => [
                'Create an accounting and expense dashboard',
                'Prepare financial statements for a mock company',
                'Build an inventory + sales report using Excel'
            ],
            'resources' => [
                sbResource('Microsoft Learn – Excel / Data', 'https://support.microsoft.com/excel'),
                sbResource('NPTEL – Management & Finance', 'https://nptel.ac.in/courses'),
                sbResource('Tally Education', 'https://tallyeducation.com/')
            ],
            'description' => 'A strong business-operations path for B.Com/BBA/MBA students interested in accounting, reporting and ERP work.'
        ],
        [
            'id' => 'ecommerce',
            'title' => 'E-Commerce & Business Growth',
            'icon' => 'bi-cart3',
            'degrees' => ['business','commerce','arts','computer'],
            'skills' => ['Digital Marketing','Excel','SEO','Google Analytics','Communication','UI/UX Design'],
            'courses' => [
                'E-Commerce Fundamentals',
                'Digital Marketing & SEO',
                'Web Analytics',
                'Customer & Conversion Analytics'
            ],
            'projects' => [
                'Create an e-commerce store prototype',
                'Build a product-sales dashboard',
                'Create an SEO + conversion improvement plan'
            ],
            'resources' => [
                sbResource('Google Skillshop', 'https://skillshop.withgoogle.com/'),
                sbResource('HubSpot Academy', 'https://academy.hubspot.com/'),
                sbResource('Shopify Learn', 'https://www.shopify.com/learn')
            ],
            'description' => 'For students interested in online business, marketplaces, growth, customer journeys and digital sales.'
        ],
        [
            'id' => 'networking',
            'title' => 'Networking & IT Support',
            'icon' => 'bi-router',
            'degrees' => ['computer','engineering','science'],
            'skills' => ['Networking','Linux','Hardware','Python','Cybersecurity','Communication'],
            'courses' => [
                'Computer Networking Fundamentals',
                'Linux Administration',
                'IT Support Fundamentals',
                'Network Security Basics'
            ],
            'projects' => [
                'Design a small office network in a simulator',
                'Build a system monitoring dashboard',
                'Document a complete IT support troubleshooting guide'
            ],
            'resources' => [
                sbResource('Cisco Networking Academy', 'https://www.netacad.com/'),
                sbResource('Microsoft Learn – Windows / IT', 'https://learn.microsoft.com/training/'),
                sbResource('Linux Foundation Training', 'https://training.linuxfoundation.org/')
            ],
            'description' => 'Learn the foundations of networks, operating systems, troubleshooting and IT infrastructure.'
        ],
    ];
}

function sbGetStudentSkillNames($conn, $studentId) {
    $names = [];
    $stmt = $conn->prepare(
        "SELECT s.skill_name, ss.proficiency
         FROM student_skills ss
         JOIN skills s ON s.id = ss.skill_id
         WHERE ss.student_id = ?"
    );
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $names[sbNormalizeText($row['skill_name'])] = $row['proficiency'];
    }
    return $names;
}

function sbSkillMatchScore($studentSkills, $targetSkills) {
    if (!$targetSkills) return 0;
    $matched = 0;
    foreach ($targetSkills as $skill) {
        $target = sbNormalizeText($skill);
        foreach ($studentSkills as $studentSkill => $level) {
            if ($studentSkill === $target || strpos($studentSkill, $target) !== false || strpos($target, $studentSkill) !== false) {
                $matched++;
                break;
            }
        }
    }
    return round(($matched / count($targetSkills)) * 100);
}

function sbMatchedSkills($studentSkills, $targetSkills) {
    $matched = [];
    foreach ($targetSkills as $skill) {
        $target = sbNormalizeText($skill);
        foreach ($studentSkills as $studentSkill => $level) {
            if ($studentSkill === $target || strpos($studentSkill, $target) !== false || strpos($target, $studentSkill) !== false) {
                $matched[] = $skill;
                break;
            }
        }
    }
    return $matched;
}

function sbCareerRecommendations($conn, $student) {
    $studentSkills = sbGetStudentSkillNames($conn, (int)$student['id']);
    $catalog = sbCareerCatalog();
    $results = [];

    foreach ($catalog as $path) {
        $degreeScore = sbCourseMatches($student['course'] ?? '', $path['degrees']);
        $skillScore = sbSkillMatchScore($studentSkills, $path['skills']);
        $profileScore = 0;
        if (!empty($student['course'])) $profileScore += 45;
        if (!empty($student['college'])) $profileScore += 25;
        if (!empty($studentSkills)) $profileScore += 30;

        $overall = round(($degreeScore * 0.40) + ($skillScore * 0.50) + ($profileScore * 0.10));
        $matched = sbMatchedSkills($studentSkills, $path['skills']);
        $missing = array_values(array_diff($path['skills'], $matched));

        $path['compatibility'] = max(0, min(100, $overall));
        $path['degree_score'] = $degreeScore;
        $path['skill_score'] = $skillScore;
        $path['profile_score'] = $profileScore;
        $path['matched_skills'] = $matched;
        $path['missing_skills'] = $missing;
        $results[] = $path;
    }

    usort($results, function($a, $b) {
        if ($a['compatibility'] === $b['compatibility']) return $b['skill_score'] <=> $a['skill_score'];
        return $b['compatibility'] <=> $a['compatibility'];
    });

    return $results;
}
