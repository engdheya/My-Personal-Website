const bcrypt = require('bcryptjs');
const db = require('./db');

function seedDatabase() {
  console.log('Seeding database with initial data...');

  // 1. Admin User
  const existingUser = db.prepare('SELECT id FROM users WHERE email = ?').get('admin@dheyadev.com');
  if (!existingUser) {
    const hashedPassword = bcrypt.hashSync('password', 10);
    db.prepare(`
      INSERT INTO users (name, email, password, avatar, role)
      VALUES (?, ?, ?, ?, ?)
    `).run('Dheya Abbas', 'admin@dheyadev.com', hashedPassword, '/images/dheya-avatar.jpg', 'admin');
    console.log('Seeded admin user: admin@dheyadev.com / password');
  }

  // 2. Settings
  const settingsData = [
    ['site_name', 'DheyaDev', 'general'],
    ['site_tagline', 'Software Engineer & Developer', 'general'],
    ['site_tagline_ar', 'مهندس ومطور برمجيات', 'general'],
    ['site_description', 'Personal website, developer portfolio and technical blog of Dheya Abbas, Software Engineer specializing in Laravel, PHP, Flutter, and scalable web solutions.', 'general'],
    ['site_description_ar', 'الموقع الشخصي ومعرض المشاريع والمدونة التقنية للمهندس ضياء عباس، متخصص في بناء حلول الويب وتطبيقات الموبايل الحديثة.', 'general'],
    ['author_name', 'Dheya Abbas', 'profile'],
    ['author_name_ar', 'ضياء عباس', 'profile'],
    ['author_title', 'Software Engineer & Developer', 'profile'],
    ['author_title_ar', 'مهندس ومطور برمجيات', 'profile'],
    ['author_bio', 'Software engineer passionate about building high-performance web applications and mobile solutions using Laravel, PHP, Flutter, and MySQL. Dedicated to clean architecture, code quality, and sharing technical knowledge.', 'profile'],
    ['author_bio_ar', 'مطور برمجيات شغوف ببناء تطبيقات الويب والموبايل عالية الكفاءة باستخدام تقنيات حديثة تشمل Laravel وPHP وFlutter وMySQL. مهتم بالبنية النظيفة ومشاركة الشروحات التقنية المفيدة.', 'profile'],
    ['profile_image', '/images/dheya-profile.jpg', 'profile'],
    ['email', 'hello@dheyadev.com', 'social'],
    ['telegram', 'https://t.me/engdheya', 'social'],
    ['github', 'https://github.com/engdheya', 'social'],
    ['linkedin', 'https://linkedin.com/in/engdheya', 'social'],
    ['instagram', 'https://instagram.com/dheyadev', 'social'],
    ['cv_url', '/uploads/Dheya-Abbas-CV.pdf', 'profile'],
    ['default_seo_title', 'Dheya Abbas | Software Engineer & Developer | DheyaDev', 'seo'],
    ['default_meta_description', 'Official portfolio and technical blog of Dheya Abbas. Articles, practical guides, and modern projects built with Laravel, Flutter, PHP, and clean code.', 'seo'],
    ['default_og_image', '/images/og-cover.png', 'seo'],
    ['google_analytics_id', '', 'analytics'],
    ['google_search_console_tag', '', 'seo'],
    ['adsense_enabled', '0', 'ads'],
    ['top_ad_code', '', 'ads'],
    ['article_ad_code', '', 'ads'],
    ['sidebar_ad_code', '', 'ads'],
    ['footer_ad_code', '', 'ads'],
  ];

  const insertSetting = db.prepare('INSERT OR IGNORE INTO settings (key, value, group_name) VALUES (?, ?, ?)');
  for (const [key, value, group] of settingsData) {
    insertSetting.run(key, value, group);
  }

  // 3. Categories
  const categoriesData = [
    {
      name: 'Laravel',
      name_ar: 'لارافيل',
      slug: 'laravel',
      description: 'Deep dives, tutorials, best practices, and architecture tips for Laravel framework.',
      description_ar: 'شروحات متقدمة وأفضل ممارسات وحلول المشاكل في إطار العمل لارافيل.',
      seo_title: 'Laravel Tutorials and Articles | DheyaDev',
      meta_description: 'Explore comprehensive Laravel guides, troubleshooting, performance optimization, and clean architecture tips by Dheya Abbas.'
    },
    {
      name: 'PHP',
      name_ar: 'بي إتش بي',
      slug: 'php',
      description: 'Modern PHP concepts, OOP, Design Patterns, and performance optimization.',
      description_ar: 'المفاهيم الحديثة للغة PHP والبرمجة كائنية التوجه وأنماط التصميم وتحسين الأداء.',
      seo_title: 'PHP Guides and Best Practices | DheyaDev',
      meta_description: 'Master modern PHP with tutorials on OOP, PSR standards, attributes, and scalable backend architecture.'
    },
    {
      name: 'Flutter',
      name_ar: 'فلاتر',
      slug: 'flutter',
      description: 'Cross-platform mobile app development with Flutter & Dart.',
      description_ar: 'تطوير تطبيقات الموبايل متعددة المنصات باستخدام Flutter وDart.',
      seo_title: 'Flutter Mobile App Development | DheyaDev',
      meta_description: 'In-depth Flutter tutorials, state management, UI architecture, and backend API integration.'
    },
    {
      name: 'MySQL',
      name_ar: 'ماي إس كيو إل',
      slug: 'mysql',
      description: 'Database schema design, indexing strategies, and query optimization.',
      description_ar: 'تصميم قواعد البيانات، استراتيجيات الفهرسة، وتحسين استعلامات SQL.',
      seo_title: 'MySQL Optimization and Schema Design | DheyaDev',
      meta_description: 'Learn database optimization, indexing techniques, and scaling strategies for MySQL and relational databases.'
    },
    {
      name: 'APIs & Architecture',
      name_ar: 'واجهات البرمجة والمعمارية',
      slug: 'apis-architecture',
      description: 'Designing scalable RESTful APIs, microservices, and system architecture.',
      description_ar: 'تصميم وتطوير واجهات RESTful API ومعمارية الأنظمة القابلة للتوسع.',
      seo_title: 'REST API Design and System Architecture | DheyaDev',
      meta_description: 'Guidelines, security patterns, authentication, and architectural blueprints for RESTful APIs.'
    },
    {
      name: 'Git & Tools',
      name_ar: 'جت والأدوات',
      slug: 'git-tools',
      description: 'Version control workflows, GitHub best practices, VS Code tricks, and developer tooling.',
      description_ar: 'إدارة الإصدارات عبر Git، ممارسات GitHub الاحترافية، وأدوات المطور الحديثة.',
      seo_title: 'Git, GitHub and Developer Tooling | DheyaDev',
      meta_description: 'Boost developer productivity with Git workflows, VS Code extensions, Postman, and CI/CD pipelines.'
    }
  ];

  const insertCategory = db.prepare(`
    INSERT OR IGNORE INTO categories (name, name_ar, slug, description, description_ar, seo_title, meta_description)
    VALUES (?, ?, ?, ?, ?, ?, ?)
  `);
  for (const cat of categoriesData) {
    insertCategory.run(cat.name, cat.name_ar, cat.slug, cat.description, cat.description_ar, cat.seo_title, cat.meta_description);
  }

  // 4. Tags
  const tagsData = [
    { name: 'Laravel', name_ar: 'لارافيل', slug: 'laravel' },
    { name: 'PHP', name_ar: 'PHP', slug: 'php' },
    { name: 'MySQL', name_ar: 'MySQL', slug: 'mysql' },
    { name: 'Flutter', name_ar: 'Flutter', slug: 'flutter' },
    { name: 'Dart', name_ar: 'Dart', slug: 'dart' },
    { name: 'REST API', name_ar: 'REST API', slug: 'rest-api' },
    { name: 'Troubleshooting', name_ar: 'حل المشاكل', slug: 'troubleshooting' },
    { name: 'Security', name_ar: 'الأمان', slug: 'security' },
    { name: 'Performance', name_ar: 'الأداء', slug: 'performance' },
    { name: 'Architecture', name_ar: 'المعمارية', slug: 'architecture' },
    { name: 'Composer', name_ar: 'كومبوزر', slug: 'composer' },
    { name: 'XAMPP', name_ar: 'XAMPP', slug: 'xampp' }
  ];

  const insertTag = db.prepare('INSERT OR IGNORE INTO tags (name, name_ar, slug) VALUES (?, ?, ?)');
  for (const tag of tagsData) {
    insertTag.run(tag.name, tag.name_ar, tag.slug);
  }

  // 5. Posts (including exact examples from user document!)
  const user = db.prepare('SELECT id FROM users LIMIT 1').get();
  const laravelCat = db.prepare('SELECT id FROM categories WHERE slug = ?').get('laravel');
  const flutterCat = db.prepare('SELECT id FROM categories WHERE slug = ?').get('flutter');
  const mysqlCat = db.prepare('SELECT id FROM categories WHERE slug = ?').get('mysql');
  const apiCat = db.prepare('SELECT id FROM categories WHERE slug = ?').get('apis-architecture');

  const existingPost = db.prepare('SELECT id FROM posts WHERE slug = ?').get('how-to-fix-laravel-mysql-driver-error');
  if (!existingPost && user && laravelCat) {
    const post1Content = `## Introduction

One of the most frequent errors encountered when setting up a fresh Laravel application or migrating to a new development environment is:

\`\`\`bash
Illuminate\\Database\\QueryException: could not find driver (SQL: select * from \`users\`)
\`\`\`

Or directly:

\`\`\`
PDOException: could not find driver
\`\`\`

This frustrating error occurs because PHP does not have the PDO MySQL extension installed or activated. In this guide, we will walk through the exact steps to diagnose and solve this issue permanently across Windows, Linux, and Docker environments.

---

## Why Does This Error Occur?

Laravel uses PHP's **PDO (PHP Data Objects)** abstraction layer to interact securely with databases. By default, Laravel is configured to use MySQL as its database driver in the \`.env\` configuration file:

\`\`\`env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dheyadev_db
DB_USERNAME=root
DB_PASSWORD=secret
\`\`\`

When Laravel attempts to query the database, it asks PHP for the \`pdo_mysql\` driver. If the driver is missing or disabled in your active \`php.ini\`, PHP throws the \`could not find driver\` exception immediately.

---

## Step 1: Verify Installed PHP Extensions

Before editing any configuration, run this command in your terminal to see which PDO drivers are loaded by your active CLI PHP:

\`\`\`bash
php -m | grep -i pdo
\`\`\`

Or check PDO drivers directly with PHP CLI:

\`\`\`bash
php -r "print_r(PDO::getAvailableDrivers());"
\`\`\`

If \`mysql\` is not listed in the array output, your active PHP installation does not have the MySQL driver enabled.

---

## Step 2: Fixing on Windows (XAMPP / Standalone PHP)

If you are developing on Windows:

1. Locate your active \`php.ini\` file by running:
\`\`\`bash
php --ini
\`\`\`
2. Open \`php.ini\` in your code editor (e.g., VS Code or Notepad).
3. Search for the following lines:
\`\`\`ini
;extension=pdo_mysql
;extension=mysqli
\`\`\`
4. Remove the leading semicolon (\`;\`) to uncomment and enable them:
\`\`\`ini
extension=pdo_mysql
extension=mysqli
\`\`\`
5. Also ensure the \`extension_dir\` directive points to your \`ext\` folder:
\`\`\`ini
extension_dir = "ext"
\`\`\`
6. Save the file and restart your Apache server or terminal session.

---

## Step 3: Fixing on Ubuntu / Debian Linux

On Linux distributions, PHP extensions are packaged separately from the core PHP package. To install the MySQL PDO extension:

\`\`\`bash
# For Ubuntu / Debian
sudo apt update
sudo apt install php-mysql

# If using a specific version such as PHP 8.2 or 8.3:
sudo apt install php8.2-mysql
\`\`\`

After installation, restart the PHP-FPM service and your web server:

\`\`\`bash
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
\`\`\`

---

## Step 4: Verification and Running Migrations

Once you have enabled or installed the driver, verify it again:

\`\`\`bash
php -r "print_r(PDO::getAvailableDrivers());"
\`\`\`

You should now see:

\`\`\`bash
Array
(
    [0] => mysql
    [1] => sqlite
)
\`\`\`

Now you can safely run your Laravel migrations without errors:

\`\`\`bash
php artisan migrate
\`\`\`

---

## Conclusion

The \`could not find driver\` error is purely an environment configuration issue and does not indicate any problem with your Laravel application code. Ensuring your active PHP binary has the \`pdo_mysql\` extension enabled solves the issue immediately. Keep this guide handy whenever configuring a new development or production server!`;

    const post1ContentAr = `## المقدمة

من أكثر الأخطاء الشائعة التي تواجه المطورين عند تشغيل مشروع لارافيل جديد أو تجهيز بيئة عمل جديدة هو الخطأ التالي:

\`\`\`bash
Illuminate\\Database\\QueryException: could not find driver (SQL: select * from \`users\`)
\`\`\`

أو بشكل مباشر:

\`\`\`
PDOException: could not find driver
\`\`\`

يحدث هذا الخطأ عندما لا يجد مفسر PHP تعريف مشغل PDO MySQL مفعلاً في ملف الإعدادات \`php.ini\`. في هذا الدليل العملي سنشرح خطوة بخطوة كيفية حل هذه المشكلة نهائياً في بيئات ويندوز ولينكس.

---

## لماذا يحدث هذا الخطأ؟

يعتمد لارافيل على طبقة **PDO (PHP Data Objects)** للاتصال بقواعد البيانات وتنفيذ الاستعلامات بأمان. في ملف \`.env\`:

\`\`\`env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dheyadev_db
DB_USERNAME=root
DB_PASSWORD=secret
\`\`\`

عند محاولة تشغيل أمر \`php artisan migrate\`، يبحث لارافيل عن مشغل \`pdo_mysql\`. وإذا كان معطلاً يظهر الخطأ فوراً.

---

## الخطوة 1: فحص المشغلات المتاحة في PHP

قبل تعديل أي ملف، نفذ الأمر التالي في الطرفية لمعرفة المشغلات المتاحة:

\`\`\`bash
php -r "print_r(PDO::getAvailableDrivers());"
\`\`\`

إذا لم يظهر \`mysql\` ضمن المصفوفة، فهذا يؤكد أن الإضافة غير مفعلة.

---

## الخطوة 2: الحل على نظام ويندوز (XAMPP / PHP)

1. افتح الطرفية واعرف مسار ملف \`php.ini\` النشط:
\`\`\`bash
php --ini
\`\`\`
2. افتح الملف وابحث عن السطر:
\`\`\`ini
;extension=pdo_mysql
\`\`\`
3. قم بإزالة الفاصلة المنقوطة (\`;\`) ليصبح هكذا:
\`\`\`ini
extension=pdo_mysql
\`\`\`
4. احفظ الملف وأعد تشغيل الخادم والطرفية.

---

## الخطوة 3: الحل على أنظمة Ubuntu / Debian

على أنظمة لينكس، تتوفر الإضافات كحزم منفصلة. كل ما عليك هو تثبيت الحزمة عبر الأمر:

\`\`\`bash
sudo apt update
sudo apt install php-mysql
# أو لنسخة محددة:
sudo apt install php8.2-mysql
\`\`\`

ثم أعد تشغيل خدمة PHP-FPM:
\`\`\`bash
sudo systemctl restart php8.2-fpm
\`\`\`

---

## الخطوة 4: التحقق وتشغيل الـ Migrations

تأكد من تفعيل المشغل:
\`\`\`bash
php -r "print_r(PDO::getAvailableDrivers());"
\`\`\`

ثم نفذ أوامر التهجير بنجاح:
\`\`\`bash
php artisan migrate
\`\`\`

---

## الخاتمة

هذا الخطأ مرتبط حصراً ببيئة تشغيل PHP ولا علاقة له بجودة كود لارافيل نفسه. تفعيل إضافة \`pdo_mysql\` يحل المشكلة مباشرة ويوفر اتصالاً سريعاً ومستقراً بقاعدة البيانات.`;

    const insPost1 = db.prepare(`
      INSERT INTO posts (
        user_id, category_id, title, title_ar, slug, excerpt, excerpt_ar, content, content_ar,
        featured_image, alt_text, status, published_at, reading_time, seo_title, meta_description, canonical_url, focus_keyword
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now', '-2 days'), ?, ?, ?, ?, ?)
    `);

    const result1 = insPost1.run(
      user.id,
      laravelCat.id,
      'How to Fix Laravel MySQL Driver Error',
      'كيف تحل مشكلة could not find driver في Laravel و MySQL',
      'how-to-fix-laravel-mysql-driver-error',
      'A complete step-by-step troubleshooting guide to solving the common "could not find driver" PDO exception in Laravel across Windows, Ubuntu, and Docker.',
      'دليل عملي شامل لحل مشكلة could not find driver الشهيرة في Laravel مع قاعدة بيانات MySQL على ويندوز ولينكس.',
      post1Content,
      post1ContentAr,
      '/images/posts/laravel-mysql-driver.jpg',
      'How to Fix Laravel MySQL Driver Error Guide',
      'published',
      5,
      'How to Fix Laravel MySQL Driver Error | DheyaDev',
      'Complete guide to fixing the Laravel "could not find driver" MySQL PDO exception on Windows, XAMPP, and Linux servers.',
      'https://dheyadev.com/blog/how-to-fix-laravel-mysql-driver-error',
      'laravel mysql driver error'
    );

    // Attach tags
    const pdoTags = ['laravel', 'php', 'mysql', 'troubleshooting'];
    for (const slug of pdoTags) {
      const tag = db.prepare('SELECT id FROM tags WHERE slug = ?').get(slug);
      if (tag) {
        db.prepare('INSERT OR IGNORE INTO post_tag (post_id, tag_id) VALUES (?, ?)').run(result1.lastInsertRowid, tag.id);
      }
    }
  }

  // Post 2: REST API with Laravel & Sanctum
  const existingPost2 = db.prepare('SELECT id FROM posts WHERE slug = ?').get('building-secure-rest-apis-laravel-sanctum');
  if (!existingPost2 && user && apiCat) {
    const post2Content = `## Introduction

Building secure, scalable, and well-structured RESTful APIs is an essential skill for modern backend engineers. Laravel makes this remarkably clean with **Laravel Sanctum**, providing a lightweight authentication system for Single Page Applications (SPAs), mobile apps, and token-based APIs.

In this tutorial, we will construct a production-ready API architecture with rate limiting, custom API resource transformers, and strict authorization policies.

---

## Setting Up Sanctum

First, ensure Sanctum is installed in your project:

\`\`\`bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan migrate
\`\`\`

Next, add the \`HasApiTokens\` trait to your \`User\` model:

\`\`\`php
namespace App\\Models;

use Illuminate\\Foundation\\Auth\\User as Authenticatable;
use Laravel\\Sanctum\\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
\`\`\`

---

## Authentication Controller

Let us implement a dedicated \`AuthController\` that handles user registration, token generation, and revocation:

\`\`\`php
namespace App\\Http\\Controllers\\Api;

use App\\Http\\Controllers\\Controller;
use App\\Models\\User;
use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\Hash;
use Illuminate\\Validation\\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials provided.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
\`\`\`

---

## Protecting Routes and Applying Rate Limiting

Define your protected routes inside \`routes/api.php\`:

\`\`\`php
use App\\Http\\Controllers\\Api\\AuthController;
use App\\Http\\Controllers\\Api\\ProjectController;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('projects', ProjectController::class);
});
\`\`\`

---

## Conclusion

By pairing Laravel Sanctum with API Resources and rate limiters, you achieve an enterprise-grade backend ready to power web and mobile clients efficiently.`;

    const post2ContentAr = `## المقدمة

يعد بناء واجهات برمجة التطبيقات (RESTful APIs) الآمنة والسريعة من أهم مهارات مهندس البرمجيات المحترف. توفر حزمة **Laravel Sanctum** نظام مصادقة خفيف وقوي لتطبيقات الموبايل والويب.

في هذا الدليل، سنبني معمارية API احترافية تدعم حماية الرموز (Tokens)، التقييد بمعدل الطلبات (Rate Limiting)، وتنسيق الردود بشكل قياسي.

---

## إعداد حزمة Sanctum

قم بتثبيت الحزمة وتشغيل التهجير:

\`\`\`bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan migrate
\`\`\`

ثم أضف السمة \`HasApiTokens\` في نموذج المستخدم \`User\`:

\`\`\`php
namespace App\\Models;

use Illuminate\\Foundation\\Auth\\User as Authenticatable;
use Laravel\\Sanctum\\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
\`\`\`

---

## وحدة التحكم في المصادقة

كتابة \`AuthController\` لإدارة تسجيل الدخول وإنشاء الرموز:

\`\`\`php
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['البيانات المدخلة غير صحيحة.'],
        ]);
    }

    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'status' => 'success',
        'token' => $token,
        'user' => $user,
    ]);
}
\`\`\`

---

## حماية المسارات وتحديد معدل الطلب

نحدد المسارات في ملف \`routes/api.php\`:

\`\`\`php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::apiResource('projects', ProjectController::class);
});
\`\`\`

---

## الخاتمة

توفر Laravel Sanctum حلاً مثالياً للمصادقة في تطبيقات الهواتف والويب دون تعقيد OAuth2، مع المحافظة على أعلى معايير الأمان وسرعة الإنجاز.`;

    const insPost2 = db.prepare(`
      INSERT INTO posts (
        user_id, category_id, title, title_ar, slug, excerpt, excerpt_ar, content, content_ar,
        featured_image, alt_text, status, published_at, reading_time, seo_title, meta_description, canonical_url, focus_keyword
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now', '-5 days'), ?, ?, ?, ?, ?)
    `);

    const result2 = insPost2.run(
      user.id,
      apiCat.id,
      'Building Secure REST APIs with Laravel 11 and Sanctum',
      'بناء واجهات REST API آمنة في Laravel 11 باستخدام Sanctum',
      'building-secure-rest-apis-laravel-sanctum',
      'Learn how to architect clean, secure, and production-ready REST APIs using Laravel 11, Sanctum token authentication, and rate limiting.',
      'دليل متكامل لتصميم وبناء واجهات REST API احترافية وآمنة باستخدام Laravel 11 و Sanctum مع حماية الطلبات وتنسيق الردود.',
      post2Content,
      post2ContentAr,
      '/images/posts/laravel-api-sanctum.jpg',
      'Building Secure REST APIs in Laravel Sanctum',
      'published',
      6,
      'Building Secure REST APIs with Laravel 11 and Sanctum | DheyaDev',
      'Learn token-based API authentication, rate limiting, and clean controllers in Laravel 11.',
      'https://dheyadev.com/blog/building-secure-rest-apis-laravel-sanctum',
      'laravel sanctum rest api'
    );

    const apiTags = ['laravel', 'rest-api', 'security', 'architecture'];
    for (const slug of apiTags) {
      const tag = db.prepare('SELECT id FROM tags WHERE slug = ?').get(slug);
      if (tag) {
        db.prepare('INSERT OR IGNORE INTO post_tag (post_id, tag_id) VALUES (?, ?)').run(result2.lastInsertRowid, tag.id);
      }
    }
  }

  // Post 3: Flutter State Management with Riverpod
  const existingPost3 = db.prepare('SELECT id FROM posts WHERE slug = ?').get('flutter-clean-architecture-riverpod');
  if (!existingPost3 && user && flutterCat) {
    const post3Content = `## Introduction

Managing state predictably in Flutter applications becomes challenging as features grow. **Riverpod** is a reactive caching and state-management framework that catches compile-time errors, removes boilerplate, and offers clean testability.

In this article, we examine how to structure a clean Flutter application architecture using Riverpod alongside Repository patterns.

---

## Why Riverpod Over Vanilla Provider?

Riverpod improves upon Provider by:
- Being compile-safe (no runtime \`ProviderNotFoundException\`).
- Not depending directly on the Flutter widget tree.
- Supporting multiple providers of the same type effortlessly.
- Simplifying asynchronous state handling via \`AsyncValue\`.

---

## Defining a StateNotifier Provider

Here is an example managing the authentication state of a user connecting to a Laravel backend:

\`\`\`dart
import 'package:flutter_riverpod/flutter_riverpod.dart';

class AuthState {
  final bool isAuthenticated;
  final String? token;
  final bool isLoading;

  const AuthState({
    this.isAuthenticated = false,
    this.token,
    this.isLoading = false,
  });

  AuthState copyWith({bool? isAuthenticated, String? token, bool? isLoading}) {
    return AuthState(
      isAuthenticated: isAuthenticated ?? this.isAuthenticated,
      token: token ?? this.token,
      isLoading: isLoading ?? this.isLoading,
    );
  }
}

class AuthNotifier extends StateNotifier<AuthState> {
  AuthNotifier() : super(const AuthState());

  Future<void> login(String email, String password) async {
    state = state.copyWith(isLoading: true);
    try {
      // Call Laravel backend API
      final token = 'sample_sanctum_token';
      state = state.copyWith(isAuthenticated: true, token: token, isLoading: false);
    } catch (e) {
      state = state.copyWith(isLoading: false);
    }
  }
}

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier();
});
\`\`\`

---

## Consuming in UI Widgets

In Flutter, widgets extend \`ConsumerWidget\` to observe state changes effortlessly:

\`\`\`dart
class LoginButton extends ConsumerWidget {
  const LoginButton({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authProvider);

    if (authState.isLoading) {
      return const CircularProgressIndicator();
    }

    return ElevatedButton(
      onPressed: () => ref.read(authProvider.notifier).login('user@test.com', 'secret'),
      child: const Text('Sign In'),
    );
  }
}
\`\`\`

---

## Conclusion

Adopting Riverpod ensures your Flutter codebase remains modular, resilient, and easy to maintain as your product scales.`;

    const post3ContentAr = `## المقدمة

تعد إدارة الحالة (State Management) من الركائز الأساسية في تطوير تطبيقات فلاتر الاحترافية. وتعتبر حزمة **Riverpod** الخيار الأكثر أماناً وموثوقية لتفادي أخطاء وقت التشغيل وتبسيط حقن التبعيات.

في هذا المقال، نستعرض بنية معمارية نظيفة لتطبيقات Flutter باستخدام Riverpod مع نمط المستودعات (Repository Pattern).

---

## مميزات Riverpod الأساسية

- التحقق من الأخطاء أثناء وقت الترجمة (Compile-time safety).
- الاستقلالية التامة عن شجرة الودجات (Widget Tree).
- دعم قوي للتعامل مع العمليات غير المتزامنة عبر \`AsyncValue\`.
- سهولة كتابة الاختبارات الآلية (Unit Testing).

---

## تعريف حالة المصادقة

مثال لإدارة حالة تسجيل الدخول عبر Riverpod والاتصال بخلفية Laravel:

\`\`\`dart
import 'package:flutter_riverpod/flutter_riverpod.dart';

class AuthState {
  final bool isAuthenticated;
  final String? token;
  final bool isLoading;

  const AuthState({this.isAuthenticated = false, this.token, this.isLoading = false});
}
\`\`\`

---

## الخاتمة

يمنحك الاعتماد على Riverpod كوداً مرتباً وسريعاً وقابلاً للتوسع بسهولة، مما يرفع من جودة تجربة المستخدم وسلاسة التطبيق.`;

    const insPost3 = db.prepare(`
      INSERT INTO posts (
        user_id, category_id, title, title_ar, slug, excerpt, excerpt_ar, content, content_ar,
        featured_image, alt_text, status, published_at, reading_time, seo_title, meta_description, canonical_url, focus_keyword
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now', '-10 days'), ?, ?, ?, ?, ?)
    `);

    const result3 = insPost3.run(
      user.id,
      flutterCat.id,
      'Flutter Architecture and State Management with Riverpod',
      'معمارية تطبيقات فلاتر وإدارة الحالة باستخدام Riverpod',
      'flutter-clean-architecture-riverpod',
      'A practical guide to structuring scalable Flutter applications with Riverpod, separation of concerns, and clean API integration.',
      'دليل تطبيقي لبناء تطبيقات فلاتر قابلة للتوسع باستخدام Riverpod مع فصل طبقات الكود والربط النظيف مع الـ API.',
      post3Content,
      post3ContentAr,
      '/images/posts/flutter-riverpod.jpg',
      'Flutter Architecture with Riverpod',
      'published',
      5,
      'Flutter Architecture and State Management with Riverpod | DheyaDev',
      'Learn how to architect clean Flutter applications with Riverpod state management and scalable clean architecture.',
      'https://dheyadev.com/blog/flutter-clean-architecture-riverpod',
      'flutter riverpod architecture'
    );

    const flutterTags = ['flutter', 'dart', 'architecture'];
    for (const slug of flutterTags) {
      const tag = db.prepare('SELECT id FROM tags WHERE slug = ?').get(slug);
      if (tag) {
        db.prepare('INSERT OR IGNORE INTO post_tag (post_id, tag_id) VALUES (?, ?)').run(result3.lastInsertRowid, tag.id);
      }
    }
  }

  // 6. Projects (including the exact "Smart Store" example from requirement 8!)
  const projectsData = [
    {
      title: 'Smart Store E-Commerce System',
      title_ar: 'منصة المتجر الذكي للتجارة الإلكترونية',
      slug: 'smart-store',
      short_description: 'Full-stack multi-vendor e-commerce platform with Laravel backend, Filament admin dashboard, MySQL, and Flutter mobile client.',
      short_description_ar: 'نظام متجر إلكتروني متكامل لإدارة المنتجات والطلبات والمستخدمين مع تطبيق موبايل Flutter ولوحة تحكم Filament.',
      description: 'Smart Store is an enterprise-grade e-commerce solution designed for seamless product management, order processing, and customer engagement. Built with a Laravel 11 REST API backend, Filament v3 administrative dashboard, optimized MySQL database, and cross-platform Flutter application for iOS and Android.',
      description_ar: 'المتجر الذكي هو حل تجارة إلكترونية متقدم مصمم لإدارة المنتجات، متابعة الطلبات، وتوفير تجربة تسوق سلسة. مبني باستخدام خلفية Laravel 11، لوحة تحكم Filament v3، قاعدة بيانات MySQL محسنة، وتطبيق هاتف متعدد المنصات بنظام Flutter.',
      problem: 'Traditional small-business retail suffered from fragmented inventory tracking, manual order reconciliation, and slow mobile user experience.',
      problem_ar: 'عانت المتاجر التقليدية من تشتت تتبع المخزون والتعامل اليدوي البطيء مع الطلبات وغياب تطبيق موبايل سريع ومتجاوب.',
      solution: 'Engineered an unified ecosystem featuring real-time inventory synchronization, instant push notifications, automated invoice generation, and a high-speed Flutter client app.',
      solution_ar: 'بناء منظومة برمجية موحدة تتيح مزامنة المخزون لحظياً، إرسال إشعارات فورية، إصدار الفواتير آلياً، وتطبيق جوال فائق السرعة وسهل الاستخدام.',
      main_image: '/images/projects/smart-store-main.jpg',
      gallery: JSON.stringify(['/images/projects/smart-store-1.jpg', '/images/projects/smart-store-2.jpg']),
      technologies: 'Laravel, Filament, MySQL, Flutter, Dart, REST API, Tailwind CSS',
      category: 'Full Stack',
      github_url: 'https://github.com/engdheya/smart-store',
      live_url: 'https://smartstore-demo.dheyadev.com',
      status: 'Completed',
      project_date: 'August 2026',
      is_featured: 1,
      features: JSON.stringify([
        'Multi-vendor product catalog with variations and inventory tracking',
        'Secure checkout with Stripe and Cash on Delivery integration',
        'Filament admin panel for sales analytics and customer management',
        'Cross-platform Flutter app with offline caching and state preservation',
        'RESTful API with Sanctum authentication and rate limiting'
      ]),
      seo_title: 'Smart Store E-Commerce Platform | DheyaDev Project',
      meta_description: 'Case study of Smart Store: A modern e-commerce solution built with Laravel 11, Filament v3, MySQL, and Flutter.'
    },
    {
      title: 'DevPulse API Monitoring Service',
      title_ar: 'نظام ديف بالس لمراقبة الـ APIs والخوادم',
      slug: 'devpulse-api-monitoring',
      short_description: 'High-speed automated uptime monitor, response time tracker, and public status page service for web endpoints.',
      short_description_ar: 'منصة مؤتمتة لفحص جاهزية الخوادم وقياس سرعة استجابة الـ APIs مع صفحات حالة عامة وإشعارات فورية.',
      description: 'DevPulse is a lightweight developer-first uptime monitoring tool. It continuously validates REST endpoints, SSL certificate health, response payloads, and notifies teams via Telegram and email when anomalies occur.',
      description_ar: 'ديف بالس هي أداة مراقبة سريعة وموجهة للمطورين لفحص جاهزية الخوادم وشهادات الأمان SSL وزمن الاستجابة وإرسال تنبيهات عبر تيليجرام والبريد.',
      problem: 'Developers often face unexpected downtime without immediate alerts or accessible status dashboards for their end users.',
      problem_ar: 'تتعرض الأنظمة لتوقف غير متوقع دون إشعارات سريعة للمطورين أو صفحة حالة تبين الوضع للجمهور.',
      solution: 'Created an automated daemon that schedules background health checks, calculates percentile latencies, and generates instant Telegram webhooks.',
      solution_ar: 'تطوير خدمة خلفية تجري فحوصات دورية مجدولة وتحسب معدلات الأداء وترسل تنبيهات تيليجرام فورية عند أي خلل.',
      main_image: '/images/projects/devpulse-main.jpg',
      gallery: JSON.stringify(['/images/projects/devpulse-1.jpg']),
      technologies: 'Laravel, PHP, SQLite, Tailwind CSS, Telegram Bot API',
      category: 'DevOps & Tools',
      github_url: 'https://github.com/engdheya/devpulse',
      live_url: 'https://devpulse.dheyadev.com',
      status: 'Completed',
      project_date: 'July 2026',
      is_featured: 1,
      features: JSON.stringify([
        'Automated cron-based health checking with sub-second precision',
        'Instant Telegram and Email webhook alert notifications',
        'Public-facing branded status pages with uptime percentages',
        'SSL certificate expiration warning system'
      ]),
      seo_title: 'DevPulse API Monitoring Platform | DheyaDev',
      meta_description: 'Case study of DevPulse: Automated server and REST API monitoring system with instant Telegram alerts.'
    },
    {
      title: 'TaskFlow Agile Project Management',
      title_ar: 'تاسك فلو لإدارة المشاريع وفرق العمل',
      slug: 'taskflow-project-management',
      short_description: 'Interactive Kanban board and sprint collaboration tool for software engineering teams.',
      short_description_ar: 'لوحة كانبان تفاعلية وأداة لتخطيط مهام البرمجة والتعاون بين فرق العمل البرمجية.',
      description: 'TaskFlow helps engineering teams organize issues, track sprint cycles, assign pull requests, and visualize delivery velocity with clean, uncluttered boards.',
      description_ar: 'يساعد تاسك فلو الفرق التقنية في تنظيم المهام، تتبع دورات العمل البرمجية، وقياس الإنتاجية عبر واجهة كانبان مرنة.',
      problem: 'Many project management tools are overloaded with unnecessary features that slow down engineering teams.',
      problem_ar: 'معظم أدوات إدارة المهام مليئة بالتعقيدات والميزات غير الضرورية التي تبطئ وتيرة العمل اليومي.',
      solution: 'Built a focused, fast, keyboard-friendly Kanban tool with markdown support, label filtering, and milestone burn-down charts.',
      solution_ar: 'بناء أداة سريعة وخفيفة تدعم اختصارات لوحة المفاتيح والماركداون وفلترة التصنيفات مع سرعة فائقة.',
      main_image: '/images/projects/taskflow-main.jpg',
      gallery: JSON.stringify([]),
      technologies: 'PHP, Laravel, Tailwind CSS, Alpine.js, MySQL',
      category: 'Web Application',
      github_url: 'https://github.com/engdheya/taskflow',
      live_url: 'https://taskflow.dheyadev.com',
      status: 'In Progress',
      project_date: 'September 2026',
      is_featured: 1,
      features: JSON.stringify([
        'Drag-and-drop Kanban workflow columns',
        'Markdown-enabled task descriptions with syntax highlighted code',
        'Role-based access control (Admin, Member, Viewer)',
        'Comprehensive audit log and timeline history'
      ]),
      seo_title: 'TaskFlow Agile Project Management | DheyaDev',
      meta_description: 'Discover TaskFlow: Clean, developer-oriented project management and sprint tracking web app.'
    }
  ];

  const insertProject = db.prepare(`
    INSERT OR IGNORE INTO projects (
      title, title_ar, slug, short_description, short_description_ar, description, description_ar,
      problem, problem_ar, solution, solution_ar, main_image, gallery, technologies, category,
      github_url, live_url, status, project_date, is_featured, features, seo_title, meta_description
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  `);

  for (const p of projectsData) {
    insertProject.run(
      p.title, p.title_ar, p.slug, p.short_description, p.short_description_ar, p.description, p.description_ar,
      p.problem, p.problem_ar, p.solution, p.solution_ar, p.main_image, p.gallery, p.technologies, p.category,
      p.github_url, p.live_url, p.status, p.project_date, p.is_featured, p.features, p.seo_title, p.meta_description
    );
  }

  // 7. Services (from requirement 21)
  const servicesData = [
    {
      title: 'Web Application Development',
      title_ar: 'تطوير تطبيقات الويب الاحترافية',
      slug: 'web-development',
      icon: 'code',
      short_description: 'Custom, scalable, and secure web applications engineered with Laravel, PHP, and modern backend standards.',
      short_description_ar: 'بناء مواقع وتطبيقات ويب متقدمة وسريعة باستخدام Laravel وPHP بأعلى معايير الأمان وقابلية التوسع.',
      description: 'I design and build tailored web applications from scratch, focusing on robust architecture, clean code conventions, seamless user experience, and long-term maintainability. Whether you need a corporate portal, SaaS platform, or customized CMS, I deliver production-ready software.',
      description_ar: 'أقوم بتصميم وتطوير تطبيقات ويب مخصصة من الصفر، بالتركيز على البنية القوية، الكود النظيف، سهولة الاستخدام، والأمان العالي لتلبية احتياجات عملك بدقة.',
      features: JSON.stringify([
        'Full-stack Laravel development with clean MVC / Service architecture',
        'Database design, normalization, and optimization',
        'Secure authentication, authorization, and role management',
        'Filament or customized administrative dashboards',
        'High performance, caching, and SEO optimization'
      ]),
      sort_order: 1
    },
    {
      title: 'Mobile App Development',
      title_ar: 'تطوير تطبيقات الموبايل (Flutter)',
      slug: 'mobile-development',
      icon: 'smartphone',
      short_description: 'Cross-platform mobile apps for iOS and Android built with Flutter & Dart with native performance.',
      short_description_ar: 'تطوير تطبيقات جوال حديثة لنظامي iOS وAndroid باستخدام Flutter وDart بأداء فائق وتصميم عصري.',
      description: 'Crafting responsive, fluid, and intuitive mobile applications that run smoothly on both Android and iOS devices. From pixel-perfect UI implementation to offline data synchronization and real-time backend communication.',
      description_ar: 'بناء تطبيقات هواتف ذكية جذابة وسريعة تعمل على أندرويد وآيفون بكفاءة عالية، مع الربط المباشر بقواعد البيانات ومزامنة البيانات في وضع عدم الاتصال.',
      features: JSON.stringify([
        'Single codebase for Android and iOS deployments',
        'Modern reactive state management with Riverpod or Bloc',
        'Offline-first architecture with local SQLite/Hive caching',
        'Push notifications, payment gateway, and maps integration',
        'Smooth animations and responsive UI across all screen sizes'
      ]),
      sort_order: 2
    },
    {
      title: 'RESTful API Engineering',
      title_ar: 'تصميم وبناء واجهات البرمجة (REST API)',
      slug: 'rest-api-development',
      icon: 'server',
      short_description: 'High-throughput, secure, and well-documented RESTful APIs to power your mobile and frontend applications.',
      short_description_ar: 'تصميم وبناء واجهات RESTful API عالية الأداء والأمان وموثقة بدقة لتغذية تطبيقات الموبايل والويب.',
      description: 'Engineering resilient API layers with token-based authentication (Sanctum/JWT), strict validation, rate limiting, and structured JSON responses that make mobile and client integration straightforward.',
      description_ar: 'تصميم طبقات API متينة تدعم التوثيق الآمن، التقييد بمعدل الطلب، والتحقق الصارم من المدخلات لضمان تكامل سلس وموثوق.',
      features: JSON.stringify([
        'Sanctum and JWT token authentication mechanisms',
        'Standardized JSON API responses and status error codes',
        'Comprehensive OpenAPI / Swagger documentation',
        'Rate limiting, CORS configuration, and threat protection',
        'Optimized query endpoints with pagination and filtering'
      ]),
      sort_order: 3
    },
    {
      title: 'Database Architecture & Tuning',
      title_ar: 'تصميم وتحسين قواعد البيانات (MySQL)',
      slug: 'database-optimization',
      icon: 'database',
      short_description: 'Relational database schema modeling, query profiling, index optimization, and performance scaling.',
      short_description_ar: 'تصميم المخططات الهيكلية لقواعد البيانات، وفهرسة الحقول، وتحسين سرعة استعلامات SQL المعقدة.',
      description: 'Ensuring your database can handle growth and heavy concurrent traffic without bottlenecks. I analyze slow queries, design efficient indexing schemes, and optimize transactions for consistency and speed.',
      description_ar: 'ضمان قدرة قاعدة البيانات على استيعاب حركة المرور العالية دون بطء، من خلال تحليل الاستعلامات البطيئة وتطبيق الفهارس المناسبة.',
      features: JSON.stringify([
        'Normalized schema design and foreign key integrity',
        'Index profiling with EXPLAIN analysis to eliminate full-table scans',
        'Caching strategy integration with Redis',
        'Data migration, seeding, and backup strategies',
        'High availability and connection pooling best practices'
      ]),
      sort_order: 4
    }
  ];

  const insertService = db.prepare(`
    INSERT OR IGNORE INTO services (
      title, title_ar, slug, icon, short_description, short_description_ar, description, description_ar, features, sort_order
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  `);

  for (const s of servicesData) {
    insertService.run(s.title, s.title_ar, s.slug, s.icon, s.short_description, s.short_description_ar, s.description, s.description_ar, s.features, s.sort_order);
  }

  // 8. Sample Contact Message
  const existingMsg = db.prepare('SELECT id FROM messages LIMIT 1').get();
  if (!existingMsg) {
    db.prepare(`
      INSERT INTO messages (name, email, subject, message, status)
      VALUES (?, ?, ?, ?, ?)
    `).run(
      'Khalid Al-Mansoor',
      'khalid@example.com',
      'Inquiry regarding Laravel & Flutter E-commerce project',
      'Hello Eng. Dheya, I saw your portfolio and Smart Store project. We are planning to build a custom marketplace app and would like to discuss working together.',
      'unread'
    );
  }

  console.log('Database seeded successfully with realistic data!');
}

if (require.main === module) {
  seedDatabase();
}

module.exports = seedDatabase;
