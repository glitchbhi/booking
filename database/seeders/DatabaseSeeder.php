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
            'email' => 'admin@thunderbooking.bt',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'owner_status' => 'none',
            'wallet_balance' => 0,
            'email_verified_at' => now(),
        ]);

        echo "✓ Admin user created (admin@thunderbooking.bt / password)\n";

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

        // Create regular users
        $regularUsers = [];
        for ($i = 1; $i <= 10; $i++) {
            $regularUsers[] = User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'owner_status' => 'none',
                'wallet_balance' => rand(500, 5000),
                'email_verified_at' => now(),
            ]);
        }

        echo "✓ Regular users created (user1-10@example.com / password)\n";

        // Create owner users
        $owners = [];
        for ($i = 1; $i <= 5; $i++) {
            $owners[] = User::create([
                'name' => "Owner $i",
                'email' => "owner$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'owner',
                'owner_status' => 'approved',
                'wallet_balance' => 0,
                'email_verified_at' => now(),
            ]);
        }

        echo "✓ Owner users created (owner1-5@example.com / password)\n";

        // Create grounds for each owner - Bhutanese cities
        $locations = ['Thimphu', 'Paro', 'Punakha', 'Phuentsholing', 'Gelephu', 'Wangdue Phodrang', 'Bumthang'];
        $groundCount = 0;

        foreach ($owners as $owner) {
            // Each owner has 2-4 grounds
            $numGrounds = rand(2, 4);
            
            for ($i = 0; $i < $numGrounds; $i++) {
                $sportType = SportsType::inRandomOrder()->first();
                $location = $locations[array_rand($locations)];
                
                $ground = Ground::create([
                    'owner_id' => $owner->id,
                    'sport_type_id' => $sportType->id,
                    'name' => $sportType->name . ' Arena ' . ($groundCount + 1),
                    'description' => "Premium {$sportType->name} facility with modern amenities and excellent lighting. Perfect for both casual and professional players.",
                    'location' => $location,
                    'address' => "Sector " . rand(1, 50) . ", $location",
                    'rate_per_hour' => rand(300, 1500),
                    'is_active' => true,
                    'average_rating' => rand(35, 50) / 10, // 3.5 to 5.0
                    'total_bookings' => rand(10, 100),
                    'total_reviews' => rand(5, 30),
                ]);

                // Add availabilities (weekdays 6 AM - 11 PM)
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                
                foreach ($days as $day) {
                    $ground->availabilities()->create([
                        'day_of_week' => $day,
                        'start_time' => '06:00:00',
                        'end_time' => '23:00:00',
                        'is_active' => true,
                    ]);
                }

                $groundCount++;
            }
        }

        echo "✓ Grounds created with availabilities ($groundCount grounds)\n";

        // Create sample bookings
        $bookingsCount = 0;
        $statuses = ['booked', 'completed', 'cancelled'];
        
        foreach ($regularUsers as $user) {
            // Each user has 1-3 bookings
            $numBookings = rand(1, 3);
            
            for ($i = 0; $i < $numBookings; $i++) {
                $ground = Ground::inRandomOrder()->first();
                $status = $statuses[array_rand($statuses)];
                
                // Random date in the past or future
                if ($status === 'completed') {
                    $startTime = now()->subDays(rand(1, 30));
                } elseif ($status === 'cancelled') {
                    $startTime = now()->subDays(rand(1, 15));
                } else {
                    $startTime = now()->addDays(rand(1, 14));
                }
                
                $durationHours = rand(1, 4);
                $endTime = $startTime->copy()->addHours($durationHours);
                $totalAmount = $durationHours * $ground->rate_per_hour;

                $ground->bookings()->create([
                    'booking_number' => 'TB-' . strtoupper(Str::random(10)),
                    'user_id' => $user->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_hours' => $durationHours,
                    'rate_per_hour' => $ground->rate_per_hour,
                    'total_amount' => $totalAmount,
                    'admin_commission' => $totalAmount * 0.02,
                    'status' => $status,
                    'booking_type' => 'online',
                ]);

                $bookingsCount++;
            }
        }

        echo "✓ Sample bookings created ($bookingsCount bookings)\n";

        echo "\n";
        echo "==================================================\n";
        echo "  Thunder Booking System - Database Seeded!\n";
        echo "==================================================\n";
        echo "\n";
        echo "Admin Credentials:\n";
        echo "  Email: admin@thunderbooking.bt\n";
        echo "  Password: password\n";
        echo "\n";
        echo "Owner Credentials:\n";
        echo "  Email: owner1@example.com (to owner5@example.com)\n";
        echo "  Password: password\n";
        echo "\n";
        echo "User Credentials:\n";
        echo "  Email: user1@example.com (to user10@example.com)\n";
        echo "  Password: password\n";
        echo "\n";
        echo "Timezone: Asia/Thimphu (BTT, GMT+6)\n";
        echo "Currency: Bhutanese Ngultrum (Nu.)\n";
        echo "\n";
        echo "Run: php artisan serve\n";
        echo "Then visit: http://localhost:8000\n";
        echo "==================================================\n";
    }
}
