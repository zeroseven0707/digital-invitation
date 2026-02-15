<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Template;
use App\Models\Invitation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@nikahin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $user = User::create([
            'name' => 'Anisa & Dian',
            'email' => 'user@nikahin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $template = Template::where('name', 'Classic Elegant')->first();

        if ($template) {
            $invitation = Invitation::create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'unique_url' => 'anisa-dian-' . Str::random(8),
                'status' => 'published',
                'bride_name' => 'Anisa Rismayanti',
                'bride_father_name' => 'Mumuh Muhlisin (ALM)',
                'bride_mother_name' => 'Siti Komariah',
                'groom_name' => 'Dian Nurdilan',
                'groom_father_name' => 'Emuh Saepulloh',
                'groom_mother_name' => 'Ii Jahroi',
                'akad_date' => '2026-03-29',
                'akad_time_start' => '08:00',
                'akad_time_end' => '10:00',
                'akad_location' => 'Rumah Mempelai Wanita',
                'reception_date' => '2026-03-29',
                'reception_time_start' => '10:00',
                'reception_time_end' => '21:00',
                'reception_location' => 'Rumah Mempelai Wanita',
                'full_address' => 'Kp. Sindangraja RT 11 RW 04 Desa Linggawangi Kec. Leuwisari Kab. Tasikmalaya',
                'google_maps_url' => 'https://maps.google.com/?q=-7.3505,108.2167',
                'is_paid' => true,
                'paid_at' => now(),
            ]);

            $this->command->info('Created invitation for: ' . $invitation->bride_name . ' & ' . $invitation->groom_name);
            $this->command->info('  URL: /i/' . $invitation->unique_url);
        } else {
            $this->command->error('Classic Elegant template not found. Please run TemplateSeeder first.');
        }
    }
}

