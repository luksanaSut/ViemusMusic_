<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@viemus.school'],
            [
                'name'     => 'ผู้ดูแลระบบ',
                'password' => Hash::make('ChangeMe123!'),
                'role'     => 'admin',
                'is_active' => true,
                'must_change_password' => true,
            ]
        );
    }
}
