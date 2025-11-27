<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@veltrix.test'],
            [
                'name' => 'VELTRIX Admin',
                'password' => bcrypt('password'), //maybe change later if we need
                'role' => 'admin',
            ]
        );
    }
}
