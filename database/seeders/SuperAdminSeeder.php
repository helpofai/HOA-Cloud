<?php

namespace Database\Seeders;

use App\Models\User;
use App\Core\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hoacloud.com'],
            [
                'name' => 'Root Admin',
                'password' => Hash::make('admin123'), // Change this in production
                'role' => UserRole::SUPER_ADMIN,
                'quota_limit' => 1099511627776, // 1TB for Super Admin
            ]
        );
    }
}
