<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── Midtrans ──────────────────────────────────────────────────────
            [
                'key'         => 'midtrans_server_key',
                'value'       => env('MIDTRANS_SERVER_KEY', ''),
                'type'        => 'secret',
                'group'       => 'midtrans',
                'label'       => 'Midtrans Server Key',
                'description' => 'Server key dari dashboard Midtrans. Jangan bagikan ke siapapun.',
                'is_public'   => false,
            ],
            [
                'key'         => 'midtrans_client_key',
                'value'       => env('MIDTRANS_CLIENT_KEY', ''),
                'type'        => 'string',
                'group'       => 'midtrans',
                'label'       => 'Midtrans Client Key',
                'description' => 'Client key dari dashboard Midtrans. Digunakan di frontend untuk inisiasi Snap pembayaran.',
                'is_public'   => true,
            ],
            [
                'key'         => 'midtrans_merchant_id',
                'value'       => env('MIDTRANS_MERCHANT_ID', ''),
                'type'        => 'string',
                'group'       => 'midtrans',
                'label'       => 'Midtrans Merchant ID',
                'description' => 'Merchant ID dari dashboard Midtrans.',
                'is_public'   => false,
            ],
            [
                'key'         => 'midtrans_is_production',
                'value'       => env('MIDTRANS_IS_PRODUCTION', 'false'),
                'type'        => 'boolean',
                'group'       => 'midtrans',
                'label'       => 'Mode Production',
                'description' => 'Aktifkan untuk menggunakan Midtrans production. Nonaktifkan untuk sandbox/testing.',
                'is_public'   => false,
            ],

            // ── Payment ───────────────────────────────────────────────────────
            [
                'key'         => 'invitation_price',
                'value'       => env('INVITATION_PRICE', '50000'),
                'type'        => 'integer',
                'group'       => 'payment',
                'label'       => 'Harga Undangan (IDR)',
                'description' => 'Harga yang dikenakan untuk mempublikasikan undangan.',
                'is_public'   => true,
            ],

            // ── App ───────────────────────────────────────────────────────────
            [
                'key'         => 'app_name',
                'value'       => 'Nikahin',
                'type'        => 'string',
                'group'       => 'app',
                'label'       => 'Nama Aplikasi',
                'description' => 'Nama aplikasi yang ditampilkan ke pengguna.',
                'is_public'   => true,
            ],
            [
                'key'         => 'app_tagline',
                'value'       => 'Undangan Digital Pernikahan Premium',
                'type'        => 'string',
                'group'       => 'app',
                'label'       => 'Tagline Aplikasi',
                'description' => 'Tagline singkat aplikasi.',
                'is_public'   => true,
            ],
            [
                'key'         => 'support_email',
                'value'       => 'pamudanyiptakarya@gmail.com',
                'type'        => 'string',
                'group'       => 'app',
                'label'       => 'Email Support',
                'description' => 'Email yang ditampilkan untuk kontak support.',
                'is_public'   => true,
            ],
            [
                'key'         => 'privacy_policy_url',
                'value'       => 'https://nikahin.online/privacy',
                'type'        => 'string',
                'group'       => 'app',
                'label'       => 'URL Kebijakan Privasi',
                'description' => 'Link halaman kebijakan privasi.',
                'is_public'   => true,
            ],
            [
                'key'         => 'terms_url',
                'value'       => 'https://nikahin.online/terms',
                'type'        => 'string',
                'group'       => 'app',
                'label'       => 'URL Syarat & Ketentuan',
                'description' => 'Link halaman syarat dan ketentuan.',
                'is_public'   => true,
            ],
            [
                'key'         => 'maintenance_mode',
                'value'       => 'false',
                'type'        => 'boolean',
                'group'       => 'app',
                'label'       => 'Mode Maintenance',
                'description' => 'Aktifkan untuk menampilkan halaman maintenance ke pengguna.',
                'is_public'   => true,
            ],
            [
                'key'         => 'app_version',
                'value'       => '1.0.0',
                'type'        => 'string',
                'group'       => 'app',
                'label'       => 'Versi Aplikasi',
                'description' => 'Versi saat ini yang ditampilkan di About screen.',
                'is_public'   => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
