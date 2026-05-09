<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@undangan.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'is_active' => true,
        ]);

        $this->command->info('✓ Users seeded successfully!');
        $this->command->info('  Admin: admin@undangan.com / admin123');
        $this->command->info('  Users: budi@example.com, siti@example.com, ahmad@example.com, dewi@example.com / password');
    }
}
