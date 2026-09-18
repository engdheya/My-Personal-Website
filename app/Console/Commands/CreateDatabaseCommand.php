<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Creates the MySQL database configured in .env when it does not exist yet.
 * Handy on a fresh XAMPP install: no need to open phpMyAdmin.
 *
 * Usage:  php artisan xampp:create-database
 */
class CreateDatabaseCommand extends Command
{
    protected $signature = 'xampp:create-database {--charset=utf8mb4} {--collation=utf8mb4_unicode_ci}';

    protected $description = 'Create the MySQL database from your .env configuration (XAMPP friendly)';

    public function handle(): int
    {
        $connection = config('database.default');

        if ($connection !== 'mysql') {
            $this->warn('The default connection is "'.$connection.'", not "mysql". Nothing to create.');

            if ($connection === 'sqlite') {
                $database = config('database.connections.sqlite.database');
                $this->line('SQLite uses a single file: '.$database);
                $this->line('Create it with: <fg=green>php artisan migrate --seed</>');
            }

            return self::SUCCESS;
        }

        $database = (string) config('database.connections.mysql.database');
        $charset = (string) $this->option('charset');
        $collation = (string) $this->option('collation');

        if ($database === '') {
            $this->error('DB_DATABASE is empty in your .env file.');

            return self::FAILURE;
        }

        try {
            $pdo = new \PDO(
                sprintf(
                    'mysql:host=%s;port=%s',
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.port', 3306)
                ),
                (string) config('database.connections.mysql.username'),
                (string) config('database.connections.mysql.password'),
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            $pdo->exec(sprintf(
                'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s',
                str_replace('`', '', $database),
                $charset,
                $collation
            ));

            $this->info('[OK] Database "'.$database.'" is ready.');

            // Verify the app can actually connect using the configured credentials.
            DB::connection('mysql')->getPdo();
            $this->info('[OK] Laravel connected successfully. Next: php artisan migrate --seed');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Could not create the database: '.$e->getMessage());
            $this->line('Make sure MySQL is running in the XAMPP control panel.');

            return self::FAILURE;
        }
    }
}
