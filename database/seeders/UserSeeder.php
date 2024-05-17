<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()
            ->create([
                'name' => 'admin',
                'email' => 'aw3r1se@gmail.com',
                'password' => 'bigdaddy',
            ]);

        User::factory()
            ->count(10)
            ->create();
    }
}
