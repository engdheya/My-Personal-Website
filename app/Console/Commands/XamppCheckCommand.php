<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * One command that tells you whether this project is ready to run on XAMPP
 * (or any PHP 8.2+ host) and prints the exact fix for anything that is missing.
 *
 * Usage:  php artisan xampp:check
 */
class XamppCheckCommand extends Command
{
    protected $signature = 'xampp:check';

    protected $description = 'Check the PHP/XAMPP environment, extensions, storage permissions and database connection';

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <fg=blue;options=bold>DheyaDev — XAMPP / PHP compatibility check</>');
        $this->newLine();

        $failed = 0;

        // 1. PHP version ----------------------------------------------------
        $phpOk = version_compare(PHP_VERSION, '8.2.0', '>=');
        $this->result('PHP version', PHP_VERSION, $phpOk, $phpOk
            ? 'Supported by Laravel 11 (requires PHP 8.2+).'
            : 'Laravel 11 needs PHP 8.2 or newer. Upgrade XAMPP or switch PHP in the XAMPP control panel.');
        $failed += $phpOk ? 0 : 1;

        // 2. Required extensions -------------------------------------------
        $extensions = [
            'ctype' => 'Filtering / validation.',
            'curl' => 'HTTP client (optional but recommended).',
            'dom' => 'XML & Blade rendering.',
            'fileinfo' => 'File uploads / MIME detection.',
            'filter' => 'Input filtering.',
            'hash' => 'Password hashing.',
            'mbstring' => 'Arabic (UTF-8) text handling — required.',
            'openssl' => 'Encryption, APP_KEY, HTTPS.',
            'pdo' => 'Database layer.',
            'session' => 'Login sessions.',
            'tokenizer' => 'Blade / Composer.',
            'xml' => 'XML output & sitemap.',
        ];

        foreach ($extensions as $extension => $why) {
            $ok = extension_loaded($extension);
            $this->result('ext-'.$extension, $ok ? 'loaded' : 'missing', $ok, $why);
            $failed += $ok ? 0 : 1;
        }

        // 3. A usable database driver --------------------------------------
        $hasPdoSqlite = extension_loaded('pdo_sqlite');
        $hasPdoMysql = extension_loaded('pdo_mysql');
        $driverOk = $hasPdoSqlite || $hasPdoMysql;

        $this->result(
            'Database driver',
            trim(($hasPdoSqlite ? 'pdo_sqlite ' : '').($hasPdoMysql ? 'pdo_mysql' : '')) ?: 'none',
            $driverOk,
            'XAMPP: uncomment extension=pdo_mysql (default) or extension=pdo_sqlite in xampp/php/php.ini, then restart Apache.'
        );
        $failed += $driverOk ? 0 : 1;

        // 4. Writable folders ----------------------------------------------
        $paths = [
            'storage' => storage_path(),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
            'public/uploads' => public_path('uploads'),
        ];

        foreach ($paths as $label => $path) {
            if (! File::isDirectory($path)) {
                File::makeDirectory($path, 0775, true, true);
            }

            $ok = File::isDirectory($path) && File::isWritable($path);

            $this->result('writable: '.$label, $ok ? 'ok' : 'not writable', $ok, $ok
                ? ''
                : 'Right click the folder > Properties > uncheck "Read-only" (Windows) or run: chmod -R 775 storage bootstrap/cache');
            $failed += $ok ? 0 : 1;
        }

        // 5. .env + APP_KEY ------------------------------------------------
        $envOk = File::exists(base_path('.env'));
        $this->result('.env file', $envOk ? 'present' : 'missing', $envOk, $envOk
            ? ''
            : 'Copy .env.example to .env (or run: copy .env.example .env) then: php artisan key:generate');
        $failed += $envOk ? 0 : 1;

        $keyOk = filled(config('app.key'));
        $this->result('APP_KEY', $keyOk ? 'set' : 'empty', $keyOk, $keyOk
            ? ''
            : 'Run: php artisan key:generate');
        $failed += $keyOk ? 0 : 1;

        // 6. Compiled CSS --------------------------------------------------
        $cssOk = File::exists(public_path('css/app.css'));
        $this->result('public/css/app.css', $cssOk ? 'present' : 'missing', $cssOk, $cssOk
            ? ''
            : 'Run: npm install && npm run build:css');
        $failed += $cssOk ? 0 : 1;

        // 7. Database connection + migrations ------------------------------
        $connection = config('database.default');
        try {
            DB::connection()->getPdo();
            $this->result('Database connection', 'connected ('.$connection.')', true, '');

            if (Setting::isReady()) {
                $counts = [
                    'posts' => DB::table('posts')->count(),
                    'projects' => DB::table('projects')->count(),
                    'services' => DB::table('services')->count(),
                    'settings' => DB::table('settings')->count(),
                ];

                $this->newLine();
                $this->line('  <fg=gray>Content:</> '.collect($counts)->map(fn ($count, $table) => $table.'='.$count)->implode(', '));

                if ($counts['settings'] === 0) {
                    $this->warn('  The database is empty. Run: php artisan migrate:fresh --seed');
                }
            } else {
                $this->warn('  Migrations have not run yet. Run: php artisan migrate --seed');
                $failed++;
            }
        } catch (\Throwable $e) {
            $this->result('Database connection', 'failed ('.$connection.')', false, $e->getMessage());
            $this->line('  <fg=gray>Check DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD in .env</>');
            $this->line('  <fg=gray>MySQL users on XAMPP: host 127.0.0.1, user root, empty password.</>');

            $database = config('database.connections.'.$connection.'.database');

            if (! File::exists((string) $database)) {
                $this->line('  <fg=gray>SQLite file not found:</> '.$database);
                $this->line('  <fg=gray>Create it with: php artisan migrate --seed</>');
            }

            $failed++;
        }

        // Result ------------------------------------------------------------
        $this->newLine();

        if ($failed === 0) {
            $this->line('  <fg=green;options=bold>[OK] Everything is ready.</> Open: '.url('/'));
            $this->newLine();

            return self::SUCCESS;
        }

        $this->line('  <fg=yellow;options=bold>[!!] '.$failed.' item(s) need attention.</> Follow the notes above, then run this command again.');
        $this->newLine();

        return self::FAILURE;
    }

    protected function result(string $label, string $value, bool $ok, string $hint = ''): void
    {
        $icon = $ok ? '<fg=green>[OK]</>' : '<fg=red>[!!]</>';
        $this->line(sprintf('  %s %-28s %s', $icon, $label, '<fg=gray>'.$value.'</>'));

        if (! $ok && $hint !== '') {
            $this->line('      <fg=yellow>-> '.$hint.'</>');
        }
    }
}
