<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'root 2',
            'email' => 'rout2@example.com',
            'password' => bcrypt('Password@567*//'),
        ]);
        $user->assignRole('Root');


        $admin = User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => bcrypt('Admin@12345'),
        ]);
        $admin->assignRole('Admin');
    }
}
