<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $user = User::updateOrCreate(
            ['email' => 'admin@dheyadev.com'],
            [
                'name' => 'Dheya Abbas',
                'password' => Hash::make('password'),
                'avatar' => '/images/dheya-avatar.jpg',
                'role' => 'admin',
            ]
        );

        // 2. Settings
        $settings = [
            'site_name' => 'DheyaDev',
            'site_tagline' => 'Software Engineer & Developer',
            'site_tagline_ar' => 'مهندس ومطور برمجيات',
            'site_description' => 'Personal website, developer portfolio and technical blog of Dheya Abbas, Software Engineer specializing in Laravel, PHP, Flutter, and scalable web solutions.',
            'site_description_ar' => 'الموقع الشخصي ومعرض المشاريع والمدونة التقنية للمهندس ضياء عباس، متخصص في بناء حلول الويب وتطبيقات الموبايل الحديثة.',
            'author_name' => 'Dheya Abbas',
            'author_name_ar' => 'ضياء عباس',
            'author_title' => 'Software Engineer & Developer',
            'author_title_ar' => 'مهندس ومطور برمجيات',
            'author_bio' => 'Software engineer passionate about building high-performance web applications and mobile solutions using Laravel, PHP, Flutter, and MySQL.',
            'author_bio_ar' => 'مطور برمجيات شغوف ببناء تطبيقات الويب والموبايل عالية الكفاءة باستخدام تقنيات حديثة تشمل Laravel وPHP وFlutter وMySQL.',
            'profile_image' => '/images/dheya-profile.jpg',
            'email' => 'hello@dheyadev.com',
            'telegram' => 'https://t.me/engdheya',
            'github' => 'https://github.com/engdheya',
            'linkedin' => 'https://linkedin.com/in/engdheya',
            'instagram' => 'https://instagram.com/dheyadev',
            'cv_url' => '/uploads/Dheya-Abbas-CV.pdf',
            'default_seo_title' => 'Dheya Abbas | Software Engineer & Developer | DheyaDev',
            'default_meta_description' => 'Official portfolio and technical blog of Dheya Abbas. Articles, practical guides, and modern projects built with Laravel, Flutter, PHP, and clean code.',
            'default_og_image' => '/images/og-cover.png',
        ];

        foreach ($settings as $k => $v) {
            Setting::set($k, $v);
        }

        // 3. Categories
        $catLaravel = Category::updateOrCreate(['slug' => 'laravel'], [
            'name' => 'Laravel',
            'name_ar' => 'لارافيل',
            'description' => 'Deep dives, tutorials, best practices, and architecture tips for Laravel framework.',
            'description_ar' => 'شروحات متقدمة وأفضل ممارسات وحلول المشاكل في إطار العمل لارافيل.',
        ]);

        $catFlutter = Category::updateOrCreate(['slug' => 'flutter'], [
            'name' => 'Flutter',
            'name_ar' => 'فلاتر',
            'description' => 'Cross-platform mobile app development with Flutter & Dart.',
            'description_ar' => 'تطوير تطبيقات الموبايل متعددة المنصات باستخدام Flutter وDart.',
        ]);

        $catApi = Category::updateOrCreate(['slug' => 'apis-architecture'], [
            'name' => 'APIs & Architecture',
            'name_ar' => 'واجهات البرمجة والمعمارية',
            'description' => 'Designing scalable RESTful APIs, microservices, and system architecture.',
            'description_ar' => 'تصميم وتطوير واجهات RESTful API ومعمارية الأنظمة القابلة للتوسع.',
        ]);

        // 4. Tags
        $tagLaravel = Tag::updateOrCreate(['slug' => 'laravel'], ['name' => 'Laravel', 'name_ar' => 'لارافيل']);
        $tagPhp = Tag::updateOrCreate(['slug' => 'php'], ['name' => 'PHP', 'name_ar' => 'PHP']);
        $tagMysql = Tag::updateOrCreate(['slug' => 'mysql'], ['name' => 'MySQL', 'name_ar' => 'MySQL']);
        $tagFlutter = Tag::updateOrCreate(['slug' => 'flutter'], ['name' => 'Flutter', 'name_ar' => 'Flutter']);
        $tagApi = Tag::updateOrCreate(['slug' => 'rest-api'], ['name' => 'REST API', 'name_ar' => 'REST API']);

        // 5. Posts (Exact example from requirement 9 & 72)
        $post1 = Post::updateOrCreate(
            ['slug' => 'how-to-fix-laravel-mysql-driver-error'],
            [
                'user_id' => $user->id,
                'category_id' => $catLaravel->id,
                'title' => 'How to Fix Laravel MySQL Driver Error',
                'title_ar' => 'كيف تحل مشكلة could not find driver في Laravel و MySQL',
                'excerpt' => 'A complete step-by-step troubleshooting guide to solving the common "could not find driver" PDO exception in Laravel across Windows, Ubuntu, and Docker.',
                'excerpt_ar' => 'دليل عملي شامل لحل مشكلة could not find driver الشهيرة في Laravel مع قاعدة بيانات MySQL على ويندوز ولينكس.',
                'content' => "## Introduction\n\nOne of the most frequent errors encountered when setting up a fresh Laravel application or migrating to a new development environment is `could not find driver`.\n\n```bash\nIlluminate\\Database\\QueryException: could not find driver\n```\n\n## Solution\n\nEnable `extension=pdo_mysql` inside your `php.ini` or install `php-mysql` on Ubuntu Linux.",
                'content_ar' => "## المقدمة\n\nمن أكثر الأخطاء الشائعة هو خطأ could not find driver.\n\n## الحل\n\nتفعيل مشغل pdo_mysql في ملف php.ini أو تثبيت الحزمة على لينكس.",
                'featured_image' => '/images/posts/laravel-mysql-driver.jpg',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'reading_time' => 5,
                'seo_title' => 'How to Fix Laravel MySQL Driver Error | DheyaDev',
                'meta_description' => 'Complete guide to fixing the Laravel "could not find driver" MySQL PDO exception on Windows, XAMPP, and Linux servers.',
            ]
        );
        $post1->tags()->sync([$tagLaravel->id, $tagPhp->id, $tagMysql->id]);

        // 6. Projects (Exact example from requirement 8)
        Project::updateOrCreate(
            ['slug' => 'smart-store'],
            [
                'title' => 'Smart Store E-Commerce System',
                'title_ar' => 'منصة المتجر الذكي للتجارة الإلكترونية',
                'short_description' => 'Full-stack multi-vendor e-commerce platform with Laravel backend, Filament admin dashboard, MySQL, and Flutter mobile client.',
                'short_description_ar' => 'نظام متجر إلكتروني متكامل لإدارة المنتجات والطلبات والمستخدمين مع تطبيق موبايل Flutter ولوحة تحكم Filament.',
                'description' => 'Smart Store is an enterprise-grade e-commerce solution designed for seamless product management, order processing, and customer engagement.',
                'description_ar' => 'المتجر الذكي هو حل تجارة إلكترونية متقدم مصمم لإدارة المنتجات، متابعة الطلبات، وتوفير تجربة تسوق سلسة.',
                'problem' => 'Fragmented inventory tracking, slow mobile performance, and manual invoice reconciliation.',
                'solution' => 'Built an integrated ecosystem with automated inventory sync, Flutter mobile app, and instant push notifications.',
                'main_image' => '/images/projects/smart-store-main.jpg',
                'technologies' => 'Laravel, Filament, MySQL, Flutter, Dart, REST API, Tailwind CSS',
                'category' => 'Full Stack',
                'github_url' => 'https://github.com/engdheya/smart-store',
                'live_url' => 'https://smartstore-demo.dheyadev.com',
                'status' => 'Completed',
                'project_date' => 'August 2026',
                'is_featured' => true,
                'features' => [
                    'Multi-vendor product catalog with inventory tracking',
                    'Filament admin panel for sales analytics',
                    'Cross-platform Flutter app with offline caching',
                ],
            ]
        );

        // 7. Services
        Service::updateOrCreate(
            ['slug' => 'web-development'],
            [
                'title' => 'Web Application Development',
                'title_ar' => 'تطوير تطبيقات الويب الاحترافية',
                'icon' => 'code',
                'short_description' => 'Custom, scalable, and secure web applications engineered with Laravel, PHP, and modern backend standards.',
                'short_description_ar' => 'بناء مواقع وتطبيقات ويب متقدمة وسريعة باستخدام Laravel وPHP بأعلى معايير الأمان وقابلية التوسع.',
                'description' => 'I design and build tailored web applications from scratch, focusing on robust architecture and clean code.',
                'sort_order' => 1,
                'features' => ['Full-stack Laravel development', 'Database design & optimization', 'Filament dashboards'],
            ]
        );

        // 8. Sample Message
        Message::updateOrCreate(
            ['email' => 'khalid@example.com'],
            [
                'name' => 'Khalid Al-Mansoor',
                'subject' => 'Inquiry regarding Laravel & Flutter E-commerce project',
                'message' => 'Hello Eng. Dheya, I saw your portfolio and Smart Store project. We are planning to build a custom marketplace app.',
                'status' => 'unread',
            ]
        );
    }
}
