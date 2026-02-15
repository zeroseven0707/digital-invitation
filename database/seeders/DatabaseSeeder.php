<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // Seed templates first
        $this->command->info('📋 Seeding templates...');
        $this->call(TemplateSeeder::class);
        $this->command->newLine();

        // Seed users (admin and regular users)
        $this->command->info('👥 Seeding users...');
        $this->call(UserSeeder::class);
        $this->command->newLine();

        // Seed sample data (invitations, guests, views)
        $this->command->info('📝 Seeding sample data...');
        $this->call(SampleDataSeeder::class);
        $this->command->newLine();

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📌 Login credentials:');
        $this->command->info('   Admin: admin@undangan.com / admin123');
        $this->command->info('   User: budi@example.com / password');
        $this->command->info('   User: siti@example.com / password');
        $this->command->info('   User: ahmad@example.com / password');
        $this->command->info('   User: dewi@example.com / password');
    }
}

