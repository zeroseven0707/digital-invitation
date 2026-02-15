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

        // Seed production data (admin, user, and one invitation)
        $this->command->info('👥 Seeding production data...');
        $this->call(ProductionSeeder::class);
        $this->command->newLine();

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📌 Login credentials:');
        $this->command->info('   Admin: admin@nikahin.com / password');
        $this->command->info('   User: user@nikahin.com / password');
    }
}
