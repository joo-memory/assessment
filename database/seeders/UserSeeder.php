<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Seed the database with FranchiseOwner and User records.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Seed 9 normal users
        foreach (range(1, 9) as $index) {
            // Generate random data
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;
            $password = Hash::make('password'); // Default password for all users

            // Insert data into franchise_owners table
            DB::table('franchise_owners')->insert([
                'name' => $name,
                'email' => $email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert data into users table
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'created_at' => now(),
                'updated_at' => now(),
                'is_admin' => 0, // Default non-admin user
            ]);
        }

        // Seed one admin user with specific email and password
        $adminName = 'Admin';
        $adminEmail = 'admin@admin.com';
        $adminPassword = Hash::make('password'); // Admin password

        // Check if the admin email already exists in franchise_owners table
        $existingAdmin = DB::table('franchise_owners')->where('email', $adminEmail)->first();
        
        if (!$existingAdmin) {
            // Insert data into franchise_owners table
            DB::table('franchise_owners')->insert([
                'name' => $adminName,
                'email' => $adminEmail,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert data into users table with is_admin set to 1
        DB::table('users')->insert([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => $adminPassword,
            'created_at' => now(),
            'updated_at' => now(),
            'is_admin' => 1, // Admin user
        ]);
    }
}
