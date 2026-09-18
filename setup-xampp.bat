@echo off
CHCP 65001 >nul
TITLE DheyaDev - XAMPP Setup (PHP 8.2)

echo ============================================================
echo   DheyaDev - تنصيب سريع على XAMPP
echo   DheyaDev - Quick setup on XAMPP (PHP 8.2)
echo ============================================================
echo.

cd /d "%~dp0"

REM ---- 1) Check PHP -------------------------------------------------------
where php >nul 2>nul
if errorlevel 1 (
    echo [X] لم يتم العثور على php في PATH.
    echo     أضف مسار XAMPP إلى PATH ثم أعد المحاولة، مثال:
    echo     set PATH=%%PATH%%;C:\xampp\php
    echo.
    pause
    exit /b 1
)

echo [i] إصدار PHP الحالي / Current PHP version:
php -v | findstr /r "^PHP"
echo.

REM ---- 2) Check Composer --------------------------------------------------
where composer >nul 2>nul
if errorlevel 1 (
    echo [!] Composer غير مثبت. حمله من https://getcomposer.org/download/
    echo     Composer was not found. Install it from getcomposer.org then re-run.
    echo.
    pause
    exit /b 1
)

REM ---- 3) .env ------------------------------------------------------------
if not exist ".env" (
    echo [1/6] إنشاء ملف .env من .env.example ...
    copy ".env.example" ".env" >nul
) else (
    echo [1/6] ملف .env موجود مسبقاً - تم التخطي.
)

REM ---- 4) Composer install ------------------------------------------------
echo [2/6] تثبيت حزم Composer (قد يستغرق عدة دقائق) ...
call composer install --no-interaction
if errorlevel 1 goto :failed

REM ---- 5) App key ---------------------------------------------------------
echo [3/6] توليد مفتاح التطبيق APP_KEY ...
call php artisan key:generate --ansi

REM ---- 6) Database --------------------------------------------------------
echo [4/6] تهيئة قاعدة البيانات (MySQL إن كانت مُعدّة في .env) ...
call php artisan xampp:create-database
call php artisan migrate --seed --force
if errorlevel 1 goto :failed

REM ---- 7) Storage link ----------------------------------------------------
echo [5/6] ربط مجلد التخزين storage:link ...
call php artisan storage:link

REM ---- 8) Verify ----------------------------------------------------------
echo [6/6] فحص البيئة والتوافق ...
call php artisan xampp:check

echo.
echo ============================================================
echo  تم التنصيب. افتح الموقع:
echo    http://localhost/dheyadev/public
echo  أو عدّل APP_URL في ملف .env ليطابق رابطك ثم:
echo    php artisan config:clear
echo.
echo  لوحة التحكم: http://localhost/dheyadev/public/admin
echo  البريد: admin@dheyadev.com  |  كلمة المرور: password
echo ============================================================
echo.
pause
exit /b 0

:failed
echo.
echo [X] فشل أحد الأوامر. أصلح الخطأ أعلاه ثم أعد تشغيل هذا الملف.
echo.
pause
exit /b 1
