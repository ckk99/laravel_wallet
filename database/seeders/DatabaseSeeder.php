<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'Admin@yopmail.com',
            'password' => 'Admin@123',
            'phone' => '9897969594'
        ]);

        $user = User::find(1); // Find the user
        $user->assignRole('Admin');

        \App\Models\User::factory()->create([
            'name' => 'Partner',
            'email' => 'Partner@yopmail.com',
            'password' => 'Partner@123',
            'phone' => '9897969593'
        ]);

        $user = User::find(2); // Find the user
        $user->assignRole('Partner');
    }
}
