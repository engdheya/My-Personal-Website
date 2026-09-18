<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Global site settings: identity, social links, SEO defaults, AdSense slots.
 *
 * Source of truth: the `settings` table of the shipped SQLite database,
 * so the MySQL and SQLite installations render byte-identical pages.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => ['DheyaDev', 'general'],
            'site_tagline' => ['Software Engineer & Developer', 'general'],
            'site_tagline_ar' => ['مهندس ومطور برمجيات', 'general'],
            'site_description' => ['Personal website, developer portfolio and technical blog of Dheya Abbas, Software Engineer specializing in Laravel, PHP, Flutter, and scalable web solutions.', 'general'],
            'site_description_ar' => ['الموقع الشخصي ومعرض المشاريع والمدونة التقنية للمهندس ضياء عباس، متخصص في بناء حلول الويب وتطبيقات الموبايل الحديثة.', 'general'],
            'author_name' => ['Dheya Abbas', 'profile'],
            'author_name_ar' => ['ضياء عباس', 'profile'],
            'author_title' => ['Software Engineer & Developer', 'profile'],
            'author_title_ar' => ['مهندس ومطور برمجيات', 'profile'],
            'author_bio' => ['Software engineer passionate about building high-performance web applications and mobile solutions using Laravel, PHP, Flutter, and MySQL. Dedicated to clean architecture, code quality, and sharing technical knowledge.', 'profile'],
            'author_bio_ar' => ['مطور برمجيات شغوف ببناء تطبيقات الويب والموبايل عالية الكفاءة باستخدام تقنيات حديثة تشمل Laravel وPHP وFlutter وMySQL. مهتم بالبنية النظيفة ومشاركة الشروحات التقنية المفيدة.', 'profile'],
            'profile_image' => ['/images/dheya-profile.jpg', 'profile'],
            'email' => ['hello@dheyadev.com', 'social'],
            'telegram' => ['https://t.me/engdheya', 'social'],
            'github' => ['https://github.com/engdheya', 'social'],
            'linkedin' => ['https://linkedin.com/in/engdheya', 'social'],
            'instagram' => ['https://instagram.com/dheyadev', 'social'],
            'cv_url' => ['/uploads/Dheya-Abbas-CV.pdf', 'profile'],
            'default_seo_title' => ['Dheya Abbas | Software Engineer & Developer | DheyaDev', 'seo'],
            'default_meta_description' => ['Official portfolio and technical blog of Dheya Abbas. Articles, practical guides, and modern projects built with Laravel, Flutter, PHP, and clean code.', 'seo'],
            'default_og_image' => ['/images/og-cover.png', 'seo'],
            'google_analytics_id' => ['', 'analytics'],
            'google_search_console_tag' => ['', 'seo'],
            'adsense_enabled' => ['0', 'ads'],
            'top_ad_code' => ['', 'ads'],
            'article_ad_code' => ['', 'ads'],
            'sidebar_ad_code' => ['', 'ads'],
            'footer_ad_code' => ['', 'ads'],
        ];

        foreach ($settings as $key => [$value, $group]) {
            Setting::set($key, $value, $group);
        }

        $this->command?->info('  [OK] ' . count($settings) . ' settings seeded');
    }
}
