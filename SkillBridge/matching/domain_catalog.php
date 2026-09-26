<?php
/**
 * SkillBridge domain-first career catalog.
 * Each domain defines suitable courses, required skills, projects and learning resources.
 */
function sbDomainCatalog() {
    return [
        'software-development' => [
            'title'=>'Software & Web Development','icon'=>'bi-code-slash','degrees'=>['computer','engineering','science'],
            'skills'=>['HTML','CSS','JavaScript','Git','MySQL','PHP','REST API'],
            'courses'=>['Web Development Fundamentals','JavaScript & REST APIs','SQL & Database Fundamentals','Git & GitHub'],
            'projects'=>['Student Placement Portal','College Event Management System','E-commerce Website with Login + MySQL'],
            'resources'=>[['name'=>'freeCodeCamp','url'=>'https://www.freecodecamp.org/learn/'],['name'=>'MDN Web Docs','url'=>'https://developer.mozilla.org/en-US/docs/Learn'],['name'=>'GitHub Skills','url'=>'https://skills.github.com/']]
        ],
        'data-analytics' => [
            'title'=>'Data Analytics & BI','icon'=>'bi-bar-chart-line','degrees'=>['business','commerce','computer','science','engineering'],
            'skills'=>['Excel','SQL','Power BI','Python','Statistics','Data Visualization'],
            'courses'=>['Advanced Excel for Analytics','SQL for Data Analysis','Power BI / BI Fundamentals','Python for Data Analysis'],
            'projects'=>['Sales Analytics Dashboard','College Placement Analytics','Customer Churn Analysis'],
            'resources'=>[['name'=>'Microsoft Learn – Power BI','url'=>'https://learn.microsoft.com/training/powerplatform/power-bi/'],['name'=>'Kaggle Learn','url'=>'https://www.kaggle.com/learn'],['name'=>'freeCodeCamp – Data Analysis','url'=>'https://www.freecodecamp.org/learn/data-analysis-with-python/']]
        ],
        'business-analytics' => [
            'title'=>'Business Analytics','icon'=>'bi-briefcase','degrees'=>['business','commerce','computer','engineering','science'],
            'skills'=>['Excel','SQL','Power BI','Statistics','Data Visualization','Communication'],
            'courses'=>['Business Analysis Fundamentals','Advanced Excel + Power BI','SQL for Business Analysts','Requirements & User Stories'],
            'projects'=>['Retail KPI Dashboard','Business Requirement Document for a College App','Sales Performance Analysis'],
            'resources'=>[['name'=>'Microsoft Learn','url'=>'https://learn.microsoft.com/training/'],['name'=>'IBM SkillsBuild','url'=>'https://skillsbuild.org/'],['name'=>'Coursera Business','url'=>'https://www.coursera.org/browse/business']]
        ],
        'ai-ml' => [
            'title'=>'Artificial Intelligence & Machine Learning','icon'=>'bi-cpu','degrees'=>['computer','engineering','science'],
            'skills'=>['Python','Statistics','Machine Learning','SQL','Data Visualization','Git'],
            'courses'=>['Python for Data Science','Machine Learning Fundamentals','Statistics for ML','Model Deployment Basics'],
            'projects'=>['Student Performance Predictor','House Price Prediction','Resume Skill Classification'],
            'resources'=>[['name'=>'Kaggle Learn','url'=>'https://www.kaggle.com/learn'],['name'=>'Google Machine Learning Crash Course','url'=>'https://developers.google.com/machine-learning/crash-course'],['name'=>'scikit-learn Tutorials','url'=>'https://scikit-learn.org/stable/tutorial/index.html']]
        ],
        'cybersecurity' => [
            'title'=>'Cybersecurity','icon'=>'bi-shield-lock','degrees'=>['computer','engineering','science'],
            'skills'=>['Networking','Linux','Python','Cybersecurity','SQL','Git'],
            'courses'=>['Networking Fundamentals','Linux Fundamentals','Cybersecurity Fundamentals','Web Security & OWASP'],
            'projects'=>['Secure Login Audit Tool','Network Monitoring Dashboard','OWASP Security Testing Lab'],
            'resources'=>[['name'=>'Cisco Networking Academy','url'=>'https://www.netacad.com/'],['name'=>'Microsoft Learn Security','url'=>'https://learn.microsoft.com/training/browse/?subjects=security'],['name'=>'OWASP Top 10','url'=>'https://owasp.org/www-project-top-ten/']]
        ],
        'cloud-devops' => [
            'title'=>'Cloud & DevOps','icon'=>'bi-cloud-arrow-up','degrees'=>['computer','engineering','science'],
            'skills'=>['Linux','Git','Docker','Cloud Computing','Python','Networking'],
            'courses'=>['Linux & Command Line','Cloud Fundamentals','Docker & Containers','CI/CD Fundamentals'],
            'projects'=>['Deploy a PHP/MySQL App on Cloud','Dockerize a Full-Stack App','GitHub Actions CI/CD Pipeline'],
            'resources'=>[['name'=>'AWS Skill Builder','url'=>'https://skillbuilder.aws/'],['name'=>'Microsoft Learn Azure','url'=>'https://learn.microsoft.com/training/azure/'],['name'=>'Docker Get Started','url'=>'https://docs.docker.com/get-started/']]
        ],
        'finance-accounting' => [
            'title'=>'Finance & Accounting','icon'=>'bi-currency-rupee','degrees'=>['commerce','business','science'],
            'skills'=>['Excel','Financial Analysis','Accounting','Power BI','SQL','Communication'],
            'courses'=>['Advanced Excel for Finance','Financial Modelling','Accounting & Financial Statements','Power BI for Finance'],
            'projects'=>['Personal Finance Dashboard','Company Financial Analysis','Budget & Cash Flow Dashboard'],
            'resources'=>[['name'=>'Khan Academy Finance','url'=>'https://www.khanacademy.org/economics-finance-domain/core-finance'],['name'=>'NPTEL','url'=>'https://nptel.ac.in/courses'],['name'=>'Microsoft Learn','url'=>'https://learn.microsoft.com/training/']]
        ],
        'digital-marketing' => [
            'title'=>'Digital Marketing','icon'=>'bi-megaphone','degrees'=>['business','commerce','arts','design'],
            'skills'=>['Digital Marketing','SEO','Social Media Marketing','Content Writing','Analytics','Communication'],
            'courses'=>['Digital Marketing Fundamentals','SEO Fundamentals','Social Media Marketing','Google Analytics Basics'],
            'projects'=>['Build a Brand Marketing Plan','SEO Audit for a Website','30-Day Social Media Campaign'],
            'resources'=>[['name'=>'Google Skillshop','url'=>'https://skillshop.withgoogle.com/'],['name'=>'HubSpot Academy','url'=>'https://academy.hubspot.com/'],['name'=>'Meta Blueprint','url'=>'https://www.facebook.com/business/learn']]
        ],
        'hr' => [
            'title'=>'Human Resources & People Analytics','icon'=>'bi-people','degrees'=>['business','commerce','arts'],
            'skills'=>['Communication','Excel','HR Analytics','Recruitment','Data Visualization','Presentation'],
            'courses'=>['HR Fundamentals','Recruitment & Talent Acquisition','HR Analytics with Excel/Power BI','Employee Engagement'],
            'projects'=>['Recruitment Funnel Dashboard','Employee Engagement Survey Analysis','Campus Hiring Plan'],
            'resources'=>[['name'=>'Coursera HR','url'=>'https://www.coursera.org/browse/business/human-resources'],['name'=>'Microsoft Learn','url'=>'https://learn.microsoft.com/training/'],['name'=>'IBM SkillsBuild','url'=>'https://skillsbuild.org/']]
        ],
        'ui-ux' => [
            'title'=>'UI/UX & Product Design','icon'=>'bi-palette','degrees'=>['design','computer','arts','business'],
            'skills'=>['UI/UX Design','Figma','Wireframing','Prototyping','User Research','Communication'],
            'courses'=>['UI/UX Fundamentals','Figma & Prototyping','User Research','Design Systems'],
            'projects'=>['Redesign a College Portal','Mobile App Prototype','Design System for a Student Product'],
            'resources'=>[['name'=>'Figma Learn','url'=>'https://help.figma.com/hc/en-us/categories/360002051613'],['name'=>'Google UX resources','url'=>'https://grow.google/certificates/ux-design/'],['name'=>'Interaction Design Foundation','url'=>'https://www.interaction-design.org/']]
        ],
        'product-management' => [
            'title'=>'Product Management','icon'=>'bi-kanban','degrees'=>['business','commerce','computer','engineering','design'],
            'skills'=>['Product Management','Communication','Data Visualization','SQL','User Research','Project Management'],
            'courses'=>['Product Management Fundamentals','Agile & Scrum','User Research','Product Analytics'],
            'projects'=>['Write a PRD for a College App','Product Roadmap + User Stories','Feature Analytics Dashboard'],
            'resources'=>[['name'=>'Atlassian Agile Guide','url'=>'https://www.atlassian.com/agile'],['name'=>'Google UX resources','url'=>'https://grow.google/certificates/ux-design/'],['name'=>'Microsoft Learn','url'=>'https://learn.microsoft.com/training/']]
        ],
        'ecommerce' => [
            'title'=>'E-Commerce & Business Operations','icon'=>'bi-cart3','degrees'=>['business','commerce','computer','arts','design'],
            'skills'=>['Excel','Digital Marketing','E-Commerce','SQL','Analytics','Communication'],
            'courses'=>['E-Commerce Fundamentals','Digital Marketing','Business Analytics','E-Commerce Operations'],
            'projects'=>['Build an E-Commerce Storefront','Product Sales Dashboard','Online Marketing Campaign'],
            'resources'=>[['name'=>'Google Skillshop','url'=>'https://skillshop.withgoogle.com/'],['name'=>'Shopify Academy','url'=>'https://www.shopify.com/learn'],['name'=>'Microsoft Learn','url'=>'https://learn.microsoft.com/training/']]
        ],
        'it-support-networking' => [
            'title'=>'IT Support & Networking','icon'=>'bi-router','degrees'=>['computer','engineering','science'],
            'skills'=>['Networking','Linux','Windows','Troubleshooting','Cybersecurity','Communication'],
            'courses'=>['IT Support Fundamentals','Networking Fundamentals','Linux Basics','Cybersecurity Basics'],
            'projects'=>['College Network Plan','IT Helpdesk Ticket System','Network Monitoring Dashboard'],
            'resources'=>[['name'=>'Cisco Networking Academy','url'=>'https://www.netacad.com/'],['name'=>'Microsoft Learn','url'=>'https://learn.microsoft.com/training/'],['name'=>'Linux Foundation Training','url'=>'https://training.linuxfoundation.org/']]
        ]
    ];
}

function sbCourseOptions() {
    return ['B.Tech','M.Tech','BCA','MCA','B.Sc','M.Sc','BBA','MBA','B.Com','M.Com','BA','MA','B.Des','Other'];
}
function sbDomainDegreeFit($course, $degrees) {
    if (!$course) return 35;
    $family = sbDegreeFamily($course);
    return in_array($family, $degrees, true) ? 100 : 45;
}
