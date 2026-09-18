<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Message;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Categories, tags, articles (full Markdown bodies), projects, services
 * and one sample contact message.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('role', 'admin')->first() ?? User::first();

        $categories = [
            [
                'slug' => 'laravel',
                'name' => 'Laravel',
                'name_ar' => 'لارافيل',
                'description' => 'Deep dives, tutorials, best practices, and architecture tips for Laravel framework.',
                'description_ar' => 'شروحات متقدمة وأفضل ممارسات وحلول المشاكل في إطار العمل لارافيل.',
                'seo_title' => 'Laravel Tutorials and Articles | DheyaDev',
                'meta_description' => 'Explore comprehensive Laravel guides, troubleshooting, performance optimization, and clean architecture tips by Dheya Abbas.',
            ],
            [
                'slug' => 'php',
                'name' => 'PHP',
                'name_ar' => 'بي إتش بي',
                'description' => 'Modern PHP concepts, OOP, Design Patterns, and performance optimization.',
                'description_ar' => 'المفاهيم الحديثة للغة PHP والبرمجة كائنية التوجه وأنماط التصميم وتحسين الأداء.',
                'seo_title' => 'PHP Guides and Best Practices | DheyaDev',
                'meta_description' => 'Master modern PHP with tutorials on OOP, PSR standards, attributes, and scalable backend architecture.',
            ],
            [
                'slug' => 'flutter',
                'name' => 'Flutter',
                'name_ar' => 'فلاتر',
                'description' => 'Cross-platform mobile app development with Flutter & Dart.',
                'description_ar' => 'تطوير تطبيقات الموبايل متعددة المنصات باستخدام Flutter وDart.',
                'seo_title' => 'Flutter Mobile App Development | DheyaDev',
                'meta_description' => 'In-depth Flutter tutorials, state management, UI architecture, and backend API integration.',
            ],
            [
                'slug' => 'mysql',
                'name' => 'MySQL',
                'name_ar' => 'ماي إس كيو إل',
                'description' => 'Database schema design, indexing strategies, and query optimization.',
                'description_ar' => 'تصميم قواعد البيانات، استراتيجيات الفهرسة، وتحسين استعلامات SQL.',
                'seo_title' => 'MySQL Optimization and Schema Design | DheyaDev',
                'meta_description' => 'Learn database optimization, indexing techniques, and scaling strategies for MySQL and relational databases.',
            ],
            [
                'slug' => 'apis-architecture',
                'name' => 'APIs & Architecture',
                'name_ar' => 'واجهات البرمجة والمعمارية',
                'description' => 'Designing scalable RESTful APIs, microservices, and system architecture.',
                'description_ar' => 'تصميم وتطوير واجهات RESTful API ومعمارية الأنظمة القابلة للتوسع.',
                'seo_title' => 'REST API Design and System Architecture | DheyaDev',
                'meta_description' => 'Guidelines, security patterns, authentication, and architectural blueprints for RESTful APIs.',
            ],
            [
                'slug' => 'git-tools',
                'name' => 'Git & Tools',
                'name_ar' => 'جت والأدوات',
                'description' => 'Version control workflows, GitHub best practices, VS Code tricks, and developer tooling.',
                'description_ar' => 'إدارة الإصدارات عبر Git، ممارسات GitHub الاحترافية، وأدوات المطور الحديثة.',
                'seo_title' => 'Git, GitHub and Developer Tooling | DheyaDev',
                'meta_description' => 'Boost developer productivity with Git workflows, VS Code extensions, Postman, and CI/CD pipelines.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $tags = [
            [
                'slug' => 'laravel',
                'name' => 'Laravel',
                'name_ar' => 'لارافيل',
            ],
            [
                'slug' => 'php',
                'name' => 'PHP',
                'name_ar' => 'PHP',
            ],
            [
                'slug' => 'mysql',
                'name' => 'MySQL',
                'name_ar' => 'MySQL',
            ],
            [
                'slug' => 'flutter',
                'name' => 'Flutter',
                'name_ar' => 'Flutter',
            ],
            [
                'slug' => 'dart',
                'name' => 'Dart',
                'name_ar' => 'Dart',
            ],
            [
                'slug' => 'rest-api',
                'name' => 'REST API',
                'name_ar' => 'REST API',
            ],
            [
                'slug' => 'troubleshooting',
                'name' => 'Troubleshooting',
                'name_ar' => 'حل المشاكل',
            ],
            [
                'slug' => 'security',
                'name' => 'Security',
                'name_ar' => 'الأمان',
            ],
            [
                'slug' => 'performance',
                'name' => 'Performance',
                'name_ar' => 'الأداء',
            ],
            [
                'slug' => 'architecture',
                'name' => 'Architecture',
                'name_ar' => 'المعمارية',
            ],
            [
                'slug' => 'composer',
                'name' => 'Composer',
                'name_ar' => 'كومبوزر',
            ],
            [
                'slug' => 'xampp',
                'name' => 'XAMPP',
                'name_ar' => 'XAMPP',
            ],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(['slug' => $tag['slug']], $tag);
        }

        $posts = [
            [
                'category_slug' => 'laravel',
                'slug' => 'how-to-fix-laravel-mysql-driver-error',
                'title' => 'How to Fix Laravel MySQL Driver Error',
                'title_ar' => 'كيف تحل مشكلة could not find driver في Laravel و MySQL',
                'excerpt' => 'A complete step-by-step troubleshooting guide to solving the common "could not find driver" PDO exception in Laravel across Windows, Ubuntu, and Docker.',
                'excerpt_ar' => 'دليل عملي شامل لحل مشكلة could not find driver الشهيرة في Laravel مع قاعدة بيانات MySQL على ويندوز ولينكس.',
                'content' => '## Introduction

One of the most frequent errors encountered when setting up a fresh Laravel application or migrating to a new development environment is:

```bash
Illuminate\\Database\\QueryException: could not find driver (SQL: select * from `users`)
```

Or directly:

```
PDOException: could not find driver
```

This frustrating error occurs because PHP does not have the PDO MySQL extension installed or activated. In this guide, we will walk through the exact steps to diagnose and solve this issue permanently across Windows, Linux, and Docker environments.

---

## Why Does This Error Occur?

Laravel uses PHP\'s **PDO (PHP Data Objects)** abstraction layer to interact securely with databases. By default, Laravel is configured to use MySQL as its database driver in the `.env` configuration file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dheyadev_db
DB_USERNAME=root
DB_PASSWORD=secret
```

When Laravel attempts to query the database, it asks PHP for the `pdo_mysql` driver. If the driver is missing or disabled in your active `php.ini`, PHP throws the `could not find driver` exception immediately.

---

## Step 1: Verify Installed PHP Extensions

Before editing any configuration, run this command in your terminal to see which PDO drivers are loaded by your active CLI PHP:

```bash
php -m | grep -i pdo
```

Or check PDO drivers directly with PHP CLI:

```bash
php -r "print_r(PDO::getAvailableDrivers());"
```

If `mysql` is not listed in the array output, your active PHP installation does not have the MySQL driver enabled.

---

## Step 2: Fixing on Windows (XAMPP / Standalone PHP)

If you are developing on Windows:

1. Locate your active `php.ini` file by running:
```bash
php --ini
```
2. Open `php.ini` in your code editor (e.g., VS Code or Notepad).
3. Search for the following lines:
```ini
;extension=pdo_mysql
;extension=mysqli
```
4. Remove the leading semicolon (`;`) to uncomment and enable them:
```ini
extension=pdo_mysql
extension=mysqli
```
5. Also ensure the `extension_dir` directive points to your `ext` folder:
```ini
extension_dir = "ext"
```
6. Save the file and restart your Apache server or terminal session.

---

## Step 3: Fixing on Ubuntu / Debian Linux

On Linux distributions, PHP extensions are packaged separately from the core PHP package. To install the MySQL PDO extension:

```bash
# For Ubuntu / Debian
sudo apt update
sudo apt install php-mysql

# If using a specific version such as PHP 8.2 or 8.3:
sudo apt install php8.2-mysql
```

After installation, restart the PHP-FPM service and your web server:

```bash
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

---

## Step 4: Verification and Running Migrations

Once you have enabled or installed the driver, verify it again:

```bash
php -r "print_r(PDO::getAvailableDrivers());"
```

You should now see:

```bash
Array
(
    [0] => mysql
    [1] => sqlite
)
```

Now you can safely run your Laravel migrations without errors:

```bash
php artisan migrate
```

---

## Conclusion

The `could not find driver` error is purely an environment configuration issue and does not indicate any problem with your Laravel application code. Ensuring your active PHP binary has the `pdo_mysql` extension enabled solves the issue immediately. Keep this guide handy whenever configuring a new development or production server!',
                'content_ar' => '## المقدمة

من أكثر الأخطاء الشائعة التي تواجه المطورين عند تشغيل مشروع لارافيل جديد أو تجهيز بيئة عمل جديدة هو الخطأ التالي:

```bash
Illuminate\\Database\\QueryException: could not find driver (SQL: select * from `users`)
```

أو بشكل مباشر:

```
PDOException: could not find driver
```

يحدث هذا الخطأ عندما لا يجد مفسر PHP تعريف مشغل PDO MySQL مفعلاً في ملف الإعدادات `php.ini`. في هذا الدليل العملي سنشرح خطوة بخطوة كيفية حل هذه المشكلة نهائياً في بيئات ويندوز ولينكس.

---

## لماذا يحدث هذا الخطأ؟

يعتمد لارافيل على طبقة **PDO (PHP Data Objects)** للاتصال بقواعد البيانات وتنفيذ الاستعلامات بأمان. في ملف `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dheyadev_db
DB_USERNAME=root
DB_PASSWORD=secret
```

عند محاولة تشغيل أمر `php artisan migrate`، يبحث لارافيل عن مشغل `pdo_mysql`. وإذا كان معطلاً يظهر الخطأ فوراً.

---

## الخطوة 1: فحص المشغلات المتاحة في PHP

قبل تعديل أي ملف، نفذ الأمر التالي في الطرفية لمعرفة المشغلات المتاحة:

```bash
php -r "print_r(PDO::getAvailableDrivers());"
```

إذا لم يظهر `mysql` ضمن المصفوفة، فهذا يؤكد أن الإضافة غير مفعلة.

---

## الخطوة 2: الحل على نظام ويندوز (XAMPP / PHP)

1. افتح الطرفية واعرف مسار ملف `php.ini` النشط:
```bash
php --ini
```
2. افتح الملف وابحث عن السطر:
```ini
;extension=pdo_mysql
```
3. قم بإزالة الفاصلة المنقوطة (`;`) ليصبح هكذا:
```ini
extension=pdo_mysql
```
4. احفظ الملف وأعد تشغيل الخادم والطرفية.

---

## الخطوة 3: الحل على أنظمة Ubuntu / Debian

على أنظمة لينكس، تتوفر الإضافات كحزم منفصلة. كل ما عليك هو تثبيت الحزمة عبر الأمر:

```bash
sudo apt update
sudo apt install php-mysql
# أو لنسخة محددة:
sudo apt install php8.2-mysql
```

ثم أعد تشغيل خدمة PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

---

## الخطوة 4: التحقق وتشغيل الـ Migrations

تأكد من تفعيل المشغل:
```bash
php -r "print_r(PDO::getAvailableDrivers());"
```

ثم نفذ أوامر التهجير بنجاح:
```bash
php artisan migrate
```

---

## الخاتمة

هذا الخطأ مرتبط حصراً ببيئة تشغيل PHP ولا علاقة له بجودة كود لارافيل نفسه. تفعيل إضافة `pdo_mysql` يحل المشكلة مباشرة ويوفر اتصالاً سريعاً ومستقراً بقاعدة البيانات.',
                'featured_image' => '/images/posts/laravel-mysql-driver.jpg',
                'alt_text' => 'How to Fix Laravel MySQL Driver Error Guide',
                'status' => 'published',
                'published_at' => '2026-09-15 23:17:46',
                'reading_time' => 5,
                'seo_title' => 'How to Fix Laravel MySQL Driver Error | DheyaDev',
                'meta_description' => 'Complete guide to fixing the Laravel "could not find driver" MySQL PDO exception on Windows, XAMPP, and Linux servers.',
                'canonical_url' => 'https://dheyadev.com/blog/how-to-fix-laravel-mysql-driver-error',
                'focus_keyword' => 'laravel mysql driver error',
                'views_count' => 2,
                'tags' => [
                    'laravel',
                    'php',
                    'mysql',
                    'troubleshooting',
                ],
            ],
            [
                'category_slug' => 'apis-architecture',
                'slug' => 'building-secure-rest-apis-laravel-sanctum',
                'title' => 'Building Secure REST APIs with Laravel 11 and Sanctum',
                'title_ar' => 'بناء واجهات REST API آمنة في Laravel 11 باستخدام Sanctum',
                'excerpt' => 'Learn how to architect clean, secure, and production-ready REST APIs using Laravel 11, Sanctum token authentication, and rate limiting.',
                'excerpt_ar' => 'دليل متكامل لتصميم وبناء واجهات REST API احترافية وآمنة باستخدام Laravel 11 و Sanctum مع حماية الطلبات وتنسيق الردود.',
                'content' => '## Introduction

Building secure, scalable, and well-structured RESTful APIs is an essential skill for modern backend engineers. Laravel makes this remarkably clean with **Laravel Sanctum**, providing a lightweight authentication system for Single Page Applications (SPAs), mobile apps, and token-based APIs.

In this tutorial, we will construct a production-ready API architecture with rate limiting, custom API resource transformers, and strict authorization policies.

---

## Setting Up Sanctum

First, ensure Sanctum is installed in your project:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan migrate
```

Next, add the `HasApiTokens` trait to your `User` model:

```php
namespace App\\Models;

use Illuminate\\Foundation\\Auth\\User as Authenticatable;
use Laravel\\Sanctum\\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
```

---

## Authentication Controller

Let us implement a dedicated `AuthController` that handles user registration, token generation, and revocation:

```php
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
            \'email\' => \'required|email\',
            \'password\' => \'required\',
            \'device_name\' => \'required|string\',
        ]);

        $user = User::where(\'email\', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                \'email\' => [\'Invalid credentials provided.\'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            \'status\' => \'success\',
            \'token\' => $token,
            \'user\' => [
                \'id\' => $user->id,
                \'name\' => $user->name,
                \'email\' => $user->email,
            ],
        ]);
    }
}
```

---

## Protecting Routes and Applying Rate Limiting

Define your protected routes inside `routes/api.php`:

```php
use App\\Http\\Controllers\\Api\\AuthController;
use App\\Http\\Controllers\\Api\\ProjectController;

Route::post(\'/login\', [AuthController::class, \'login\'])->middleware(\'throttle:5,1\');

Route::middleware([\'auth:sanctum\', \'throttle:60,1\'])->group(function () {
    Route::get(\'/user\', function (Request $request) {
        return $request->user();
    });
    Route::apiResource(\'projects\', ProjectController::class);
});
```

---

## Conclusion

By pairing Laravel Sanctum with API Resources and rate limiters, you achieve an enterprise-grade backend ready to power web and mobile clients efficiently.',
                'content_ar' => '## المقدمة

يعد بناء واجهات برمجة التطبيقات (RESTful APIs) الآمنة والسريعة من أهم مهارات مهندس البرمجيات المحترف. توفر حزمة **Laravel Sanctum** نظام مصادقة خفيف وقوي لتطبيقات الموبايل والويب.

في هذا الدليل، سنبني معمارية API احترافية تدعم حماية الرموز (Tokens)، التقييد بمعدل الطلبات (Rate Limiting)، وتنسيق الردود بشكل قياسي.

---

## إعداد حزمة Sanctum

قم بتثبيت الحزمة وتشغيل التهجير:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan migrate
```

ثم أضف السمة `HasApiTokens` في نموذج المستخدم `User`:

```php
namespace App\\Models;

use Illuminate\\Foundation\\Auth\\User as Authenticatable;
use Laravel\\Sanctum\\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
```

---

## وحدة التحكم في المصادقة

كتابة `AuthController` لإدارة تسجيل الدخول وإنشاء الرموز:

```php
public function login(Request $request)
{
    $request->validate([
        \'email\' => \'required|email\',
        \'password\' => \'required\',
        \'device_name\' => \'required|string\',
    ]);

    $user = User::where(\'email\', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            \'email\' => [\'البيانات المدخلة غير صحيحة.\'],
        ]);
    }

    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        \'status\' => \'success\',
        \'token\' => $token,
        \'user\' => $user,
    ]);
}
```

---

## حماية المسارات وتحديد معدل الطلب

نحدد المسارات في ملف `routes/api.php`:

```php
Route::middleware([\'auth:sanctum\', \'throttle:60,1\'])->group(function () {
    Route::apiResource(\'projects\', ProjectController::class);
});
```

---

## الخاتمة

توفر Laravel Sanctum حلاً مثالياً للمصادقة في تطبيقات الهواتف والويب دون تعقيد OAuth2، مع المحافظة على أعلى معايير الأمان وسرعة الإنجاز.',
                'featured_image' => '/images/posts/laravel-api-sanctum.jpg',
                'alt_text' => 'Building Secure REST APIs in Laravel Sanctum',
                'status' => 'published',
                'published_at' => '2026-09-12 23:17:46',
                'reading_time' => 6,
                'seo_title' => 'Building Secure REST APIs with Laravel 11 and Sanctum | DheyaDev',
                'meta_description' => 'Learn token-based API authentication, rate limiting, and clean controllers in Laravel 11.',
                'canonical_url' => 'https://dheyadev.com/blog/building-secure-rest-apis-laravel-sanctum',
                'focus_keyword' => 'laravel sanctum rest api',
                'views_count' => 0,
                'tags' => [
                    'laravel',
                    'rest-api',
                    'security',
                    'architecture',
                ],
            ],
            [
                'category_slug' => 'flutter',
                'slug' => 'flutter-clean-architecture-riverpod',
                'title' => 'Flutter Architecture and State Management with Riverpod',
                'title_ar' => 'معمارية تطبيقات فلاتر وإدارة الحالة باستخدام Riverpod',
                'excerpt' => 'A practical guide to structuring scalable Flutter applications with Riverpod, separation of concerns, and clean API integration.',
                'excerpt_ar' => 'دليل تطبيقي لبناء تطبيقات فلاتر قابلة للتوسع باستخدام Riverpod مع فصل طبقات الكود والربط النظيف مع الـ API.',
                'content' => '## Introduction

Managing state predictably in Flutter applications becomes challenging as features grow. **Riverpod** is a reactive caching and state-management framework that catches compile-time errors, removes boilerplate, and offers clean testability.

In this article, we examine how to structure a clean Flutter application architecture using Riverpod alongside Repository patterns.

---

## Why Riverpod Over Vanilla Provider?

Riverpod improves upon Provider by:
- Being compile-safe (no runtime `ProviderNotFoundException`).
- Not depending directly on the Flutter widget tree.
- Supporting multiple providers of the same type effortlessly.
- Simplifying asynchronous state handling via `AsyncValue`.

---

## Defining a StateNotifier Provider

Here is an example managing the authentication state of a user connecting to a Laravel backend:

```dart
import \'package:flutter_riverpod/flutter_riverpod.dart\';

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
      final token = \'sample_sanctum_token\';
      state = state.copyWith(isAuthenticated: true, token: token, isLoading: false);
    } catch (e) {
      state = state.copyWith(isLoading: false);
    }
  }
}

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier();
});
```

---

## Consuming in UI Widgets

In Flutter, widgets extend `ConsumerWidget` to observe state changes effortlessly:

```dart
class LoginButton extends ConsumerWidget {
  const LoginButton({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authProvider);

    if (authState.isLoading) {
      return const CircularProgressIndicator();
    }

    return ElevatedButton(
      onPressed: () => ref.read(authProvider.notifier).login(\'user@test.com\', \'secret\'),
      child: const Text(\'Sign In\'),
    );
  }
}
```

---

## Conclusion

Adopting Riverpod ensures your Flutter codebase remains modular, resilient, and easy to maintain as your product scales.',
                'content_ar' => '## المقدمة

تعد إدارة الحالة (State Management) من الركائز الأساسية في تطوير تطبيقات فلاتر الاحترافية. وتعتبر حزمة **Riverpod** الخيار الأكثر أماناً وموثوقية لتفادي أخطاء وقت التشغيل وتبسيط حقن التبعيات.

في هذا المقال، نستعرض بنية معمارية نظيفة لتطبيقات Flutter باستخدام Riverpod مع نمط المستودعات (Repository Pattern).

---

## مميزات Riverpod الأساسية

- التحقق من الأخطاء أثناء وقت الترجمة (Compile-time safety).
- الاستقلالية التامة عن شجرة الودجات (Widget Tree).
- دعم قوي للتعامل مع العمليات غير المتزامنة عبر `AsyncValue`.
- سهولة كتابة الاختبارات الآلية (Unit Testing).

---

## تعريف حالة المصادقة

مثال لإدارة حالة تسجيل الدخول عبر Riverpod والاتصال بخلفية Laravel:

```dart
import \'package:flutter_riverpod/flutter_riverpod.dart\';

class AuthState {
  final bool isAuthenticated;
  final String? token;
  final bool isLoading;

  const AuthState({this.isAuthenticated = false, this.token, this.isLoading = false});
}
```

---

## الخاتمة

يمنحك الاعتماد على Riverpod كوداً مرتباً وسريعاً وقابلاً للتوسع بسهولة، مما يرفع من جودة تجربة المستخدم وسلاسة التطبيق.',
                'featured_image' => '/images/posts/flutter-riverpod.jpg',
                'alt_text' => 'Flutter Architecture with Riverpod',
                'status' => 'published',
                'published_at' => '2026-09-07 23:17:46',
                'reading_time' => 5,
                'seo_title' => 'Flutter Architecture and State Management with Riverpod | DheyaDev',
                'meta_description' => 'Learn how to architect clean Flutter applications with Riverpod state management and scalable clean architecture.',
                'canonical_url' => 'https://dheyadev.com/blog/flutter-clean-architecture-riverpod',
                'focus_keyword' => 'flutter riverpod architecture',
                'views_count' => 0,
                'tags' => [
                    'flutter',
                    'dart',
                    'architecture',
                ],
            ],
        ];

        foreach ($posts as $data) {
            $tagSlugs = $data['tags'];
            $categorySlug = $data['category_slug'];
            unset($data['tags'], $data['category_slug']);

            $data['user_id'] = $author?->id;
            $data['category_id'] = Category::where('slug', $categorySlug)->value('id');

            $post = Post::updateOrCreate(['slug' => $data['slug']], $data);
            $post->tags()->sync(Tag::whereIn('slug', $tagSlugs)->pluck('id'));
        }

        $projects = [
            [
                'slug' => 'smart-store',
                'title' => 'Smart Store E-Commerce System',
                'title_ar' => 'منصة المتجر الذكي للتجارة الإلكترونية',
                'short_description' => 'Full-stack multi-vendor e-commerce platform with Laravel backend, Filament admin dashboard, MySQL, and Flutter mobile client.',
                'short_description_ar' => 'نظام متجر إلكتروني متكامل لإدارة المنتجات والطلبات والمستخدمين مع تطبيق موبايل Flutter ولوحة تحكم Filament.',
                'description' => 'Smart Store is an enterprise-grade e-commerce solution designed for seamless product management, order processing, and customer engagement. Built with a Laravel 11 REST API backend, Filament v3 administrative dashboard, optimized MySQL database, and cross-platform Flutter application for iOS and Android.',
                'description_ar' => 'المتجر الذكي هو حل تجارة إلكترونية متقدم مصمم لإدارة المنتجات، متابعة الطلبات، وتوفير تجربة تسوق سلسة. مبني باستخدام خلفية Laravel 11، لوحة تحكم Filament v3، قاعدة بيانات MySQL محسنة، وتطبيق هاتف متعدد المنصات بنظام Flutter.',
                'problem' => 'Traditional small-business retail suffered from fragmented inventory tracking, manual order reconciliation, and slow mobile user experience.',
                'problem_ar' => 'عانت المتاجر التقليدية من تشتت تتبع المخزون والتعامل اليدوي البطيء مع الطلبات وغياب تطبيق موبايل سريع ومتجاوب.',
                'solution' => 'Engineered an unified ecosystem featuring real-time inventory synchronization, instant push notifications, automated invoice generation, and a high-speed Flutter client app.',
                'solution_ar' => 'بناء منظومة برمجية موحدة تتيح مزامنة المخزون لحظياً، إرسال إشعارات فورية، إصدار الفواتير آلياً، وتطبيق جوال فائق السرعة وسهل الاستخدام.',
                'main_image' => '/images/projects/smart-store-main.jpg',
                'technologies' => 'Laravel, Filament, MySQL, Flutter, Dart, REST API, Tailwind CSS',
                'category' => 'Full Stack',
                'github_url' => 'https://github.com/engdheya/smart-store',
                'live_url' => 'https://smartstore-demo.dheyadev.com',
                'status' => 'Completed',
                'project_date' => 'August 2026',
                'is_featured' => true,
                'seo_title' => 'Smart Store E-Commerce Platform | DheyaDev Project',
                'meta_description' => 'Case study of Smart Store: A modern e-commerce solution built with Laravel 11, Filament v3, MySQL, and Flutter.',
                'gallery' => [
                    '/images/projects/smart-store-1.jpg',
                    '/images/projects/smart-store-2.jpg',
                ],
                'features' => [
                    'Multi-vendor product catalog with variations and inventory tracking',
                    'Secure checkout with Stripe and Cash on Delivery integration',
                    'Filament admin panel for sales analytics and customer management',
                    'Cross-platform Flutter app with offline caching and state preservation',
                    'RESTful API with Sanctum authentication and rate limiting',
                ],
            ],
            [
                'slug' => 'devpulse-api-monitoring',
                'title' => 'DevPulse API Monitoring Service',
                'title_ar' => 'نظام ديف بالس لمراقبة الـ APIs والخوادم',
                'short_description' => 'High-speed automated uptime monitor, response time tracker, and public status page service for web endpoints.',
                'short_description_ar' => 'منصة مؤتمتة لفحص جاهزية الخوادم وقياس سرعة استجابة الـ APIs مع صفحات حالة عامة وإشعارات فورية.',
                'description' => 'DevPulse is a lightweight developer-first uptime monitoring tool. It continuously validates REST endpoints, SSL certificate health, response payloads, and notifies teams via Telegram and email when anomalies occur.',
                'description_ar' => 'ديف بالس هي أداة مراقبة سريعة وموجهة للمطورين لفحص جاهزية الخوادم وشهادات الأمان SSL وزمن الاستجابة وإرسال تنبيهات عبر تيليجرام والبريد.',
                'problem' => 'Developers often face unexpected downtime without immediate alerts or accessible status dashboards for their end users.',
                'problem_ar' => 'تتعرض الأنظمة لتوقف غير متوقع دون إشعارات سريعة للمطورين أو صفحة حالة تبين الوضع للجمهور.',
                'solution' => 'Created an automated daemon that schedules background health checks, calculates percentile latencies, and generates instant Telegram webhooks.',
                'solution_ar' => 'تطوير خدمة خلفية تجري فحوصات دورية مجدولة وتحسب معدلات الأداء وترسل تنبيهات تيليجرام فورية عند أي خلل.',
                'main_image' => '/images/projects/devpulse-main.jpg',
                'technologies' => 'Laravel, PHP, SQLite, Tailwind CSS, Telegram Bot API',
                'category' => 'DevOps & Tools',
                'github_url' => 'https://github.com/engdheya/devpulse',
                'live_url' => 'https://devpulse.dheyadev.com',
                'status' => 'Completed',
                'project_date' => 'July 2026',
                'is_featured' => true,
                'seo_title' => 'DevPulse API Monitoring Platform | DheyaDev',
                'meta_description' => 'Case study of DevPulse: Automated server and REST API monitoring system with instant Telegram alerts.',
                'gallery' => [
                    '/images/projects/devpulse-1.jpg',
                ],
                'features' => [
                    'Automated cron-based health checking with sub-second precision',
                    'Instant Telegram and Email webhook alert notifications',
                    'Public-facing branded status pages with uptime percentages',
                    'SSL certificate expiration warning system',
                ],
            ],
            [
                'slug' => 'taskflow-project-management',
                'title' => 'TaskFlow Agile Project Management',
                'title_ar' => 'تاسك فلو لإدارة المشاريع وفرق العمل',
                'short_description' => 'Interactive Kanban board and sprint collaboration tool for software engineering teams.',
                'short_description_ar' => 'لوحة كانبان تفاعلية وأداة لتخطيط مهام البرمجة والتعاون بين فرق العمل البرمجية.',
                'description' => 'TaskFlow helps engineering teams organize issues, track sprint cycles, assign pull requests, and visualize delivery velocity with clean, uncluttered boards.',
                'description_ar' => 'يساعد تاسك فلو الفرق التقنية في تنظيم المهام، تتبع دورات العمل البرمجية، وقياس الإنتاجية عبر واجهة كانبان مرنة.',
                'problem' => 'Many project management tools are overloaded with unnecessary features that slow down engineering teams.',
                'problem_ar' => 'معظم أدوات إدارة المهام مليئة بالتعقيدات والميزات غير الضرورية التي تبطئ وتيرة العمل اليومي.',
                'solution' => 'Built a focused, fast, keyboard-friendly Kanban tool with markdown support, label filtering, and milestone burn-down charts.',
                'solution_ar' => 'بناء أداة سريعة وخفيفة تدعم اختصارات لوحة المفاتيح والماركداون وفلترة التصنيفات مع سرعة فائقة.',
                'main_image' => '/images/projects/taskflow-main.jpg',
                'technologies' => 'PHP, Laravel, Tailwind CSS, Alpine.js, MySQL',
                'category' => 'Web Application',
                'github_url' => 'https://github.com/engdheya/taskflow',
                'live_url' => 'https://taskflow.dheyadev.com',
                'status' => 'In Progress',
                'project_date' => 'September 2026',
                'is_featured' => true,
                'seo_title' => 'TaskFlow Agile Project Management | DheyaDev',
                'meta_description' => 'Discover TaskFlow: Clean, developer-oriented project management and sprint tracking web app.',
                'gallery' => [],
                'features' => [
                    'Drag-and-drop Kanban workflow columns',
                    'Markdown-enabled task descriptions with syntax highlighted code',
                    'Role-based access control (Admin, Member, Viewer)',
                    'Comprehensive audit log and timeline history',
                ],
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['slug' => $project['slug']], $project);
        }

        $services = [
            [
                'slug' => 'web-development',
                'title' => 'Web Application Development',
                'title_ar' => 'تطوير تطبيقات الويب الاحترافية',
                'icon' => 'code',
                'short_description' => 'Custom, scalable, and secure web applications engineered with Laravel, PHP, and modern backend standards.',
                'short_description_ar' => 'بناء مواقع وتطبيقات ويب متقدمة وسريعة باستخدام Laravel وPHP بأعلى معايير الأمان وقابلية التوسع.',
                'description' => 'I design and build tailored web applications from scratch, focusing on robust architecture, clean code conventions, seamless user experience, and long-term maintainability. Whether you need a corporate portal, SaaS platform, or customized CMS, I deliver production-ready software.',
                'description_ar' => 'أقوم بتصميم وتطوير تطبيقات ويب مخصصة من الصفر، بالتركيز على البنية القوية، الكود النظيف، سهولة الاستخدام، والأمان العالي لتلبية احتياجات عملك بدقة.',
                'sort_order' => 1,
                'features' => [
                    'Full-stack Laravel development with clean MVC / Service architecture',
                    'Database design, normalization, and optimization',
                    'Secure authentication, authorization, and role management',
                    'Filament or customized administrative dashboards',
                    'High performance, caching, and SEO optimization',
                ],
            ],
            [
                'slug' => 'mobile-development',
                'title' => 'Mobile App Development',
                'title_ar' => 'تطوير تطبيقات الموبايل (Flutter)',
                'icon' => 'smartphone',
                'short_description' => 'Cross-platform mobile apps for iOS and Android built with Flutter & Dart with native performance.',
                'short_description_ar' => 'تطوير تطبيقات جوال حديثة لنظامي iOS وAndroid باستخدام Flutter وDart بأداء فائق وتصميم عصري.',
                'description' => 'Crafting responsive, fluid, and intuitive mobile applications that run smoothly on both Android and iOS devices. From pixel-perfect UI implementation to offline data synchronization and real-time backend communication.',
                'description_ar' => 'بناء تطبيقات هواتف ذكية جذابة وسريعة تعمل على أندرويد وآيفون بكفاءة عالية، مع الربط المباشر بقواعد البيانات ومزامنة البيانات في وضع عدم الاتصال.',
                'sort_order' => 2,
                'features' => [
                    'Single codebase for Android and iOS deployments',
                    'Modern reactive state management with Riverpod or Bloc',
                    'Offline-first architecture with local SQLite/Hive caching',
                    'Push notifications, payment gateway, and maps integration',
                    'Smooth animations and responsive UI across all screen sizes',
                ],
            ],
            [
                'slug' => 'rest-api-development',
                'title' => 'RESTful API Engineering',
                'title_ar' => 'تصميم وبناء واجهات البرمجة (REST API)',
                'icon' => 'server',
                'short_description' => 'High-throughput, secure, and well-documented RESTful APIs to power your mobile and frontend applications.',
                'short_description_ar' => 'تصميم وبناء واجهات RESTful API عالية الأداء والأمان وموثقة بدقة لتغذية تطبيقات الموبايل والويب.',
                'description' => 'Engineering resilient API layers with token-based authentication (Sanctum/JWT), strict validation, rate limiting, and structured JSON responses that make mobile and client integration straightforward.',
                'description_ar' => 'تصميم طبقات API متينة تدعم التوثيق الآمن، التقييد بمعدل الطلب، والتحقق الصارم من المدخلات لضمان تكامل سلس وموثوق.',
                'sort_order' => 3,
                'features' => [
                    'Sanctum and JWT token authentication mechanisms',
                    'Standardized JSON API responses and status error codes',
                    'Comprehensive OpenAPI / Swagger documentation',
                    'Rate limiting, CORS configuration, and threat protection',
                    'Optimized query endpoints with pagination and filtering',
                ],
            ],
            [
                'slug' => 'database-optimization',
                'title' => 'Database Architecture & Tuning',
                'title_ar' => 'تصميم وتحسين قواعد البيانات (MySQL)',
                'icon' => 'database',
                'short_description' => 'Relational database schema modeling, query profiling, index optimization, and performance scaling.',
                'short_description_ar' => 'تصميم المخططات الهيكلية لقواعد البيانات، وفهرسة الحقول، وتحسين سرعة استعلامات SQL المعقدة.',
                'description' => 'Ensuring your database can handle growth and heavy concurrent traffic without bottlenecks. I analyze slow queries, design efficient indexing schemes, and optimize transactions for consistency and speed.',
                'description_ar' => 'ضمان قدرة قاعدة البيانات على استيعاب حركة المرور العالية دون بطء، من خلال تحليل الاستعلامات البطيئة وتطبيق الفهارس المناسبة.',
                'sort_order' => 4,
                'features' => [
                    'Normalized schema design and foreign key integrity',
                    'Index profiling with EXPLAIN analysis to eliminate full-table scans',
                    'Caching strategy integration with Redis',
                    'Data migration, seeding, and backup strategies',
                    'High availability and connection pooling best practices',
                ],
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }

        Message::updateOrCreate(['email' => 'khalid@example.com'], [
            'name' => 'Khalid Al-Mansoor',
            'email' => 'khalid@example.com',
            'subject' => 'Inquiry regarding Laravel & Flutter E-commerce project',
            'message' => 'Hello Eng. Dheya, I saw your portfolio and Smart Store project. We are planning to build a custom marketplace app and would like to discuss working together.',
            'status' => 'unread',
            'ip_address' => null,
        ]);

        $this->command?->info(sprintf(
            '  [OK] %d categories, %d tags, %d posts, %d projects, %d services',
            count($categories), count($tags), count($posts), count($projects), count($services)
        ));
    }
}
