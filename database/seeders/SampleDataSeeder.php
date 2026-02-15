<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Template;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Gallery;
use App\Models\InvitationView;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding sample data...');

        // Get users (skip admin)
        $users = User::where('is_admin', false)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No regular users found. Please run UserSeeder first.');
            return;
        }

        // Get templates
        $templates = Template::all();

        if ($templates->isEmpty()) {
            $this->command->warn('No templates found. Please run TemplateSeeder first.');
            return;
        }

        // Sample invitation data
        $sampleInvitations = [
            [
                'bride_name' => 'Sarah Wijaya',
                'bride_father_name' => 'Bapak Wijaya Kusuma',
                'bride_mother_name' => 'Ibu Siti Rahayu',
                'groom_name' => 'Andi Pratama',
                'groom_father_name' => 'Bapak Pratama Jaya',
                'groom_mother_name' => 'Ibu Dewi Sartika',
                'akad_date' => '2024-12-15',
                'akad_time_start' => '08:00',
                'akad_time_end' => '10:00',
                'akad_location' => 'Masjid Agung Jakarta',
                'reception_date' => '2024-12-15',
                'reception_time_start' => '18:00',
                'reception_time_end' => '21:00',
                'reception_location' => 'Grand Ballroom Hotel Mulia',
                'full_address' => 'Jl. Asia Afrika No. 8, Jakarta Pusat',
                'google_maps_url' => 'https://maps.google.com/?q=-6.2088,106.8456',
                'status' => 'published',
            ],
            [
                'bride_name' => 'Putri Ayu',
                'bride_father_name' => 'Bapak Ayu Santoso',
                'bride_mother_name' => 'Ibu Ratna Sari',
                'groom_name' => 'Reza Firmansyah',
                'groom_father_name' => 'Bapak Firmansyah Hadi',
                'groom_mother_name' => 'Ibu Lestari Wati',
                'akad_date' => '2025-01-20',
                'akad_time_start' => '09:00',
                'akad_time_end' => '11:00',
                'akad_location' => 'Masjid Al-Ikhlas',
                'reception_date' => '2025-01-20',
                'reception_time_start' => '19:00',
                'reception_time_end' => '22:00',
                'reception_location' => 'Gedung Serbaguna Merdeka',
                'full_address' => 'Jl. Merdeka No. 45, Bandung',
                'google_maps_url' => 'https://maps.google.com/?q=-6.9175,107.6191',
                'status' => 'published',
            ],
            [
                'bride_name' => 'Dina Mariana',
                'bride_father_name' => 'Bapak Mariana Putra',
                'bride_mother_name' => 'Ibu Sari Indah',
                'groom_name' => 'Fajar Nugroho',
                'groom_father_name' => 'Bapak Nugroho Adi',
                'groom_mother_name' => 'Ibu Wulan Dari',
                'akad_date' => '2025-02-14',
                'akad_time_start' => '10:00',
                'akad_time_end' => '12:00',
                'akad_location' => 'Masjid Raya Surabaya',
                'reception_date' => '2025-02-14',
                'reception_time_start' => '18:30',
                'reception_time_end' => '21:30',
                'reception_location' => 'Ballroom Hotel Majapahit',
                'full_address' => 'Jl. Tunjungan No. 65, Surabaya',
                'google_maps_url' => 'https://maps.google.com/?q=-7.2575,112.7521',
                'status' => 'draft',
            ],
        ];

        // Guest categories
        $guestCategories = ['family', 'friend', 'colleague'];

        // Sample guest names
        $guestNames = [
            'family' => [
                'Keluarga Besar Wijaya', 'Keluarga Besar Santoso', 'Keluarga Besar Pratama',
                'Om Budi & Keluarga', 'Tante Siti & Keluarga', 'Kakek Nenek',
                'Adik Rina', 'Kakak Doni', 'Sepupu Andi', 'Paman Hadi'
            ],
            'friend' => [
                'Rini Susanti', 'Agus Setiawan', 'Maya Sari', 'Dedi Kurniawan',
                'Lina Marlina', 'Bambang Wijaya', 'Eka Putri', 'Hendra Gunawan',
                'Fitri Handayani', 'Yudi Prasetyo', 'Nisa Amalia', 'Rizal Fauzi'
            ],
            'colleague' => [
                'Tim Marketing', 'Tim IT', 'Pak Direktur', 'Bu Manager',
                'Rekan Kerja Divisi A', 'Rekan Kerja Divisi B', 'Bapak CEO',
                'Ibu HRD', 'Tim Finance', 'Tim Operations'
            ]
        ];

        foreach ($users as $index => $user) {
            if ($index >= count($sampleInvitations)) {
                break;
            }

            $invitationData = $sampleInvitations[$index];
            $template = $templates->random();

            // Create invitation
            $invitation = Invitation::create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'unique_url' => $invitationData['status'] === 'published' ? Str::random(32) : null,
                'status' => $invitationData['status'],
                'bride_name' => $invitationData['bride_name'],
                'bride_father_name' => $invitationData['bride_father_name'],
                'bride_mother_name' => $invitationData['bride_mother_name'],
                'groom_name' => $invitationData['groom_name'],
                'groom_father_name' => $invitationData['groom_father_name'],
                'groom_mother_name' => $invitationData['groom_mother_name'],
                'akad_date' => $invitationData['akad_date'],
                'akad_time_start' => $invitationData['akad_time_start'],
                'akad_time_end' => $invitationData['akad_time_end'],
                'akad_location' => $invitationData['akad_location'],
                'reception_date' => $invitationData['reception_date'],
                'reception_time_start' => $invitationData['reception_time_start'],
                'reception_time_end' => $invitationData['reception_time_end'],
                'reception_location' => $invitationData['reception_location'],
                'full_address' => $invitationData['full_address'],
                'google_maps_url' => $invitationData['google_maps_url'],
            ]);

            $this->command->info("  ✓ Created invitation: {$invitationData['bride_name']} & {$invitationData['groom_name']}");

            // Add guests for each category
            foreach ($guestCategories as $category) {
                $names = $guestNames[$category];
                $count = rand(3, 5);

                for ($i = 0; $i < $count; $i++) {
                    if (isset($names[$i])) {
                        Guest::create([
                            'invitation_id' => $invitation->id,
                            'name' => $names[$i],
                            'category' => $category,
                        ]);
                    }
                }
            }

            $guestCount = $invitation->guests()->count();
            $this->command->info("    → Added {$guestCount} guests");

            // Add views for published invitations
            if ($invitation->status === 'published') {
                $viewCount = rand(10, 50);

                for ($i = 0; $i < $viewCount; $i++) {
                    InvitationView::create([
                        'invitation_id' => $invitation->id,
                        'ip_address' => $this->generateRandomIp(),
                        'user_agent' => $this->getRandomUserAgent(),
                        'device_type' => $this->getRandomDeviceType(),
                        'browser' => $this->getRandomBrowser(),
                        'viewed_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                    ]);
                }

                $this->command->info("    → Added {$viewCount} views");
            }
        }

        $this->command->info('✓ Sample data seeded successfully!');
    }

    /**
     * Generate random IP address
     */
    private function generateRandomIp(): string
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
    }

    /**
     * Get random user agent
     */
    private function getRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36',
        ];

        return $userAgents[array_rand($userAgents)];
    }

    /**
     * Get random device type
     */
    private function getRandomDeviceType(): string
    {
        $devices = ['desktop', 'mobile', 'tablet'];
        return $devices[array_rand($devices)];
    }

    /**
     * Get random browser
     */
    private function getRandomBrowser(): string
    {
        $browsers = ['Chrome', 'Safari', 'Firefox', 'Edge', 'Opera'];
        return $browsers[array_rand($browsers)];
    }
}
