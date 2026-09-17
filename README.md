# 🌐 DheyaDev - Personal Website, Technical Blog & Developer Portfolio

[![Domain](https://img.shields.io/badge/Domain-dheyadev.com-blue?style=for-the-badge)](https://dheyadev.com)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-emerald?style=for-the-badge)](https://dheyadev.com)
[![Architecture](https://img.shields.io/badge/Architecture-Clean%20%26%20SSR-indigo?style=for-the-badge)](https://dheyadev.com)

منصة شخصية احترافيّة لمهندس ومطور البرمجيات **ضياء عباس (Dheya Abbas)**، تجمع بين:
1. **معرض الأعمال (Portfolio)** لعرض المشاريع البرمجية المكتملة وتطبيقات الهواتف والأنظمة.
2. **مدونة تقنية (Technical Blog)** متخصصة لشروحات Laravel و Flutter و PHP و MySQL وحلول المشاكل البرمجية.
3. **صفحة الخدمات (Services)** لاستعراض الاستشارات البرمجية وحلول تطوير الويب والموبايل.
4. **نظام إدارة محتوى متكامل (Filament Admin CMS)** لإدارة المحتوى بالكامل بكل سهولة.
5. **جاهزية تامة لـ SEO ومحركات البحث (Schema.org, Dynamic XML Sitemap, Robots.txt, Google Search Console)**.
6. **دعم كامل للغتين العربية والإنجليزية مع محاذاة RTL / LTR والوضع الداكن والفاتح (Dark / Light Mode)**.

---

## 📑 فهرس المحتويات / Table of Contents
1. [المميزات الرئيسية / Key Features](#-المميزات-الرئيسية--key-features)
2. [بيانات تسجيل الدخول للوحة التحكم / Admin Credentials](#-بيانات-تسجيل-الدخول-للوحة-التحكم--admin-credentials)
3. [التشغيل السريع والمعاينة الحية / Quick Start & Live Preview](#-التشغيل-السريع-والمعاينة-الحية--quick-start--live-preview)
4. [هيكل المشروع / Project Architecture](#-هيكل-المشروع--project-architecture)
5. [متغيرات البيئة / Environment Variables](#-متغيرات-البيئة--environment-variables)
6. [قواعد البيانات والتهجير / Database & Migrations](#-قواعد-البيانات-والتهجير--database--migrations)
7. [بناء التنسيقات والأصول / Asset Compiling](#-بناء-التنسيقات-والأصول--asset-compiling)
8. [خطوات النشر على السيرفر / Production Deployment](#-خطوات-النشر-على-السيرفر--production-deployment)
9. [محركات البحث والأرشفة / SEO & Indexing](#-محركات-البحث-والأرشفة--seo--indexing)
10. [النسخ الاحتياطي / Backup Strategy](#-النسخ-الاحتياطي--backup-strategy)

---

## 🌟 المميزات الرئيسية / Key Features

- **واجهة مستخدم عصرية وسريعة (Server-Side Rendered)**: تعتمد على Blade / SSR مع Tailwind CSS بدون أي مكتبات SPA ثقيلة تضر بالسرعة أو الأرشفة.
- **ثنائية اللغة (Arabic & English)**: تبديل سلس مع ضبط اتجاه الصفحة تلقائياً (`dir="rtl"` أو `dir="ltr"`).
- **أكواد برمجية LTR دائماً**: تبقى كتل الأكواد البرمجية دائماً من اليسار لليمين مع ميزة **النسخ بنقرة واحدة (1-Click Copy)** وتمييز الصيغة البرمجية (Syntax Highlighting).
- **فهرس محتويات تلقائي (Dynamic Table of Contents)**: يتم توليده تلقائياً من وسوم `<h2>` و `<h3>` في المقالات.
- **حساب وقت القراءة**: تقدير تلقائي لزمن القراءة بالدقائق بناءً على عدد الكلمات.
- **لوحة تحكم Admin مستوحاة من Filament**:
  - لوحة إحصائيات ببطاقات موجزة للمقالات والمسودات والمشاريع والرسائل.
  - CRUD كامل للمقالات مع معاينة مباشرة لكيفية ظهور الرابط على بحث جوجل (Google Search Preview).
  - CRUD كامل للمشاريع مع معرض صور وتفاصيل التحدي والحل البرمجي وروابط GitHub و Live Demo.
  - CRUD كامل للتصنيفات والوسوم والخدمات.
  - إدارة رسائل التواصل مع تمييز المقروء وغير المقروء.
  - صفحة إعدادات عامة للتحكم في روابط التواصل (GitHub, Telegram, LinkedIn), وبيانات الكاتب ونصوص SEO الافتراضية وخانات إعلانات Google AdSense.
- **حماية ونماذج تواصل آمنة**: مزودة بـ Honeypot خفي ضد الـ Spam، والتحقق الصارم من المدخلات، وتخزين الرسائل فورياً في قاعدة البيانات.
- **خريطة موقع حية (`/sitemap.xml`) وملف توجيه الروبوتات (`/robots.txt`)**: تستثني صفحات الإدارة والمسودات وتحدث تلقائياً.

---

## 🔐 بيانات تسجيل الدخول للوحة التحكم / Admin Credentials

- **رابط الدخول**: `https://dheyadev.com/admin` (أو مسار `/admin` على خادم التطوير)
- **البريد الإلكتروني**: `admin@dheyadev.com`
- **كلمة المرور الافتراضية**: `password`

*(يمكن تغيير كلمة المرور أو البريد في أي وقت من لوحة التحكم أو ملف الإعدادات).*

---

## 🚀 التشغيل السريع والمعاينة الحية / Quick Start & Live Preview

يحتوي هذا المستودع على:
1. **المحرك الفوري للمعاينة الحية (SSR Engine)**: يعمل مباشرة في البيئة الحالية ومزود بقاعدة بيانات SQLite جاهزة ومملوءة بالمحتوى.
2. **شفرة Laravel 11 الكاملة**: جاهزة للنشر والتشغيل المباشر على خوادم PHP/MySQL.

### 1. تشغيل المعاينة الحية الآن:
```bash
# تثبيت التبعيات (إذا لم تكن مثبتة)
npm install

# بناء ملفات التنسيق Tailwind CSS
npm run build:css

# تشغيل الخادم على المنفذ 8000
npm start
```
سيعمل الموقع مباشرة على الرابط: `http://localhost:8000` أو المعاينة السحابية المرفوعة.

### 2. تشغيل المشروع عبر بيئة Laravel & PHP:
```bash
# تثبيت حزم الملحن Composer
composer install

# إنشاء مفتاح التطبيق
php artisan key:generate

# تشغيل التهجير وتعبئة البيانات الأولية
php artisan migrate --seed

# ربط التخزين العام للملفات المرفوعة
php artisan storage:link

# تشغيل خادم التطوير
php artisan serve
```

---

## 📂 هيكل المشروع / Project Architecture

```
├── app/
│   ├── Filament/Resources/       # تعريفات لوحة تحكم Filament v3 للمقالات والمشاريع والخدمات
│   ├── Http/Controllers/        # وحدات التحكم (Home, Post, Project, Service, Contact, Search, Sitemap)
│   ├── Models/                   # نماذج Eloquent (User, Post, Category, Tag, Project, Service, Message, Setting)
│   └── Policies/                 # سياسات الصلاحيات والحماية
├── bootstrap/app.php             # تهيئة Laravel 11
├── config/                       # ملفات الإعدادات (app, database, filament, mail)
├── database/
│   ├── database.sqlite           # قاعدة بيانات SQLite الحية والمحسنة
│   ├── migrations/               # ملفات التهجير لجميع الجداول التسعة
│   └── seeders/                  # البيانات الأولية الواقعية لمشاريع ومقالات ضياء عباس
├── public/                       # الأصول العامة (CSS, JavaScript, Images, Favicon, Robots.txt)
├── resources/
│   ├── css/app.css               # ملف مدخلات Tailwind CSS
│   └── views/                    # قوالب العرض (Blade و EJS للمعاينة المتطابقة)
├── routes/
│   ├── web.php                   # مسارات الويب العامة
│   └── api.php                   # مسارات واجهات REST API
├── src/                          # محرك الـ SSR السريع للمعاينة التفاعلية الفورية
│   ├── server.js                 # خادم Express SSR متكامل
│   ├── db.js                     # محرك SQLite فائق السرعة
│   └── helpers.js                # معالجة الماركداون والـ TOC والـ SEO والـ Schemas
├── artisan                       # واجهة سطر أوامر لارافيل
├── composer.json                 # حزم PHP ولارافيل
└── package.json                  # حزم Node و Tailwind CLI
```

---

## ⚙️ متغيرات البيئة / Environment Variables

أنشئ ملف `.env` عبر نسخ النموذج `.env.example`:
```bash
cp .env.example .env
```

أهم المتغيرات:
```env
APP_NAME="DheyaDev"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dheyadev.com

# إعدادات قاعدة البيانات (SQLite للتطوير أو MySQL للإنتاج)
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# أو MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dheyadev_db
# DB_USERNAME=dheya_user
# DB_PASSWORD=your_secure_password

# البريد الإلكتروني لإشعارات التواصل
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="hello@dheyadev.com"
```

---

## 🗄️ قواعد البيانات والتهجير / Database & Migrations

تتضمن قاعدة البيانات الجداول الأساسية المعتمدة وفق أفضل ممارسات هندسة البرمجيات:
- `users`: المستخدمين وصلاحيات الإدارة.
- `posts`: المقالات، المسودات، العناوين الثنائية، slugs، وتفاصيل الـ SEO.
- `categories`: التصنيفات التقنية.
- `tags` و `post_tag`: وسوم المقالات والعلاقة المتعددة.
- `projects`: المشاريع، التقنيات، صور المعرض، روابط العرض التجريبي و GitHub.
- `services`: الخدمات البرمجية ومميزاتها.
- `messages`: رسائل نموذج التواصل مع تتبع حالة القراءة.
- `settings`: الإعدادات العامة للموقع والشبكات الاجتماعية والـ SEO والإعلانات.

لإعادة التهجير وتعبئة البيانات في أي وقت:
```bash
# في بيئة Node:
npm run seed

# في بيئة Laravel:
php artisan migrate:fresh --seed
```

---

## 🎨 بناء التنسيقات والأصول / Asset Compiling

تم استخدام أحدث إصدار من Tailwind CSS:
```bash
# بناء نسخة الإنتاج المضغوطة
npm run build:css
```

---

## 🌐 خطوات النشر على السيرفر / Production Deployment

### 1. إعداد Nginx Virtual Host:
```nginx
server {
    listen 80;
    server_name dheyadev.com www.dheyadev.com;
    return 301 https://dheyadev.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name www.dheyadev.com;
    return 301 https://dheyadev.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name dheyadev.com;
    root /var/www/dheyadev/public;

    ssl_certificate /etc/letsencrypt/live/dheyadev.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/dheyadev.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.svg { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 2. تفعيل التخزين والمجلدات:
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔍 محركات البحث والأرشفة / SEO & Indexing

- **خريطة الموقع التفاعلية**: متوفرة عبر الرابط: `https://dheyadev.com/sitemap.xml`.
- **ملف الروبوتات**: يمنع فهرسة مسار `/admin` ويسمح بجميع الصفحات العامة عبر `https://dheyadev.com/robots.txt`.
- **البيانات المهيكلة (Schema.org JSON-LD)**:
  - `Person Schema`: للتعريف بالمهندس ضياء عباس وروابطه المهنية.
  - `BlogPosting Schema`: لكل مقال تقني مع تاريخ النشر والتعديل والمؤلف.
  - `BreadcrumbList Schema`: لتسلسل التنقل الداخلي في كافة الصفحات.
- **Google Search Console**: يمكن وضع رمز التحقق في صفحة Settings من لوحة التحكم ليتم إدراجه تلقائياً في الوسم `<head>`.
- **جاهزية إعلانات Google AdSense**: تتوفر مساحات إعلانية معدة في التصميم (أعلى الصفحة، داخل المقال، الشريط الجانبي، أسفل الصفحة) ويمكن تفعيلها وإيقافها بضغطة زر دون المساس بسرعة الموقع.

---

## 💾 النسخ الاحتياطي / Backup Strategy

1. **نسخ قاعدة البيانات (MySQL)**:
```bash
mysqldump -u dheya_user -p dheyadev_db > backup_$(date +%F).sql
```
2. **نسخ قاعدة البيانات (SQLite)**:
```bash
sqlite3 database/database.sqlite ".backup backup_$(date +%F).sqlite"
```
3. **نسخ المرفقات والصور المرفوعة**:
```bash
tar -czvf uploads_backup_$(date +%F).tar.gz public/uploads/ storage/app/public/
```

---

## 👨‍💻 المهندس والمطور / Author

**Dheya Abbas (ضياء عباس)**  
- Website: [dheyadev.com](https://dheyadev.com)  
- GitHub: [@engdheya](https://github.com/engdheya)  
- Telegram: [@engdheya](https://t.me/engdheya)  
- LinkedIn: [Dheya Abbas](https://linkedin.com/in/engdheya)  
- Email: [hello@dheyadev.com](mailto:hello@dheyadev.com)

---
© 2026 DheyaDev. All Rights Reserved.
