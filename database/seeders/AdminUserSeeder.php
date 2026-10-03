<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = trim((string) env('RMS_ADMIN_USERNAME'));
        $password = (string) env('RMS_ADMIN_PASSWORD');

        if ($username === '' || $password === '') {
            throw new RuntimeException('Set RMS_ADMIN_USERNAME and RMS_ADMIN_PASSWORD in .env before seeding the initial admin.');
        }

        User::updateOrCreate(
            ['username' => $username],
            [
                'name' => env('RMS_ADMIN_NAME', 'System Administrator'),
                'password' => Hash::make($password),
                'role' => 'admin',
            ],
        );
    }
}
