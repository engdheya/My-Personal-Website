<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * The administrator that can sign in to the Filament CMS at /admin.
 *
 * Credentials come from .env (ADMIN_EMAIL / ADMIN_PASSWORD) and default to
 * admin@dheyadev.com / password.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@dheyadev.com');
        $password = (string) env('ADMIN_PASSWORD', 'password');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) env('ADMIN_NAME', 'Dheya Abbas'),
                'password' => Hash::make($password),
                'avatar' => '/images/dheya-avatar.jpg',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info('  [OK] admin user: '.$email.' / '.$password);
    }
}
