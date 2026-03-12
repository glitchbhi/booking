<?php

namespace Database\Seeders;

use App\Models\Ground;
use App\Models\SportsType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'thunderbooking975@gmail.com',
            'password' => Hash::make('Thunder@booking123'),
            'role' => 'admin',
            'owner_status' => 'none',
            'email_verified_at' => now(),
        ]);

        echo "✓ Admin user created (thunderbooking975@gmail.com / Thunder@booking123)\n";

        // Create sports types
        $sports = [
            ['name' => 'Football', 'slug' => 'football', 'description' => 'Soccer/Football grounds'],
            ['name' => 'Cricket', 'slug' => 'cricket', 'description' => 'Cricket pitches and nets'],
            ['name' => 'Basketball', 'slug' => 'basketball', 'description' => 'Basketball courts'],
            ['name' => 'Archery', 'slug' => 'archery', 'description' => 'Traditional Bhutanese archery ranges'],
            ['name' => 'Badminton', 'slug' => 'badminton', 'description' => 'Badminton courts'],
            ['name' => 'Volleyball', 'slug' => 'volleyball', 'description' => 'Volleyball courts'],
            ['name' => 'Khuru', 'slug' => 'khuru', 'description' => 'Traditional dart throwing grounds'],
            ['name' => 'Table Tennis', 'slug' => 'table-tennis', 'description' => 'Table tennis facilities'],
        ];

        foreach ($sports as $sport) {
            SportsType::create($sport);
        }

        echo "✓ Sports types created\n";

        echo "\n";
        echo "==================================================\n";
        echo "  Thunder Booking System - Database Seeded!\n";
        echo "==================================================\n";
        echo "\n";
        echo "Admin Credentials:\n";
        echo "  Email: thunderbooking975@gmail.com\n";
        echo "  Password: Thunder@booking123\n";
        echo "\n";
        echo "Timezone: Asia/Thimphu (BTT, GMT+6)\n";
        echo "Currency: Bhutanese Ngultrum (Nu.)\n";
        echo "\n";
        echo "==================================================\n";
    }
}
