<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Classic Elegant',
                'description' => 'Template undangan dengan desain klasik dan elegan. Menggunakan warna soft red dan pink dengan animasi yang halus. Cocok untuk pernikahan dengan tema tradisional modern.',
                'thumbnail_path' => 'templates/classic-elegant/thumbnail.svg',
                'html_path' => 'templates/classic-elegant/template.html',
                'css_path' => 'templates/classic-elegant/style.css',
                'js_path' => 'templates/classic-elegant/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Modern Minimalist',
                'description' => 'Template undangan dengan desain modern dan minimalis. Menggunakan warna navy dan gold dengan layout yang bersih. Cocok untuk pernikahan dengan tema kontemporer.',
                'thumbnail_path' => 'templates/modern-minimalist/thumbnail.svg',
                'html_path' => 'templates/modern-minimalist/template.html',
                'css_path' => 'templates/modern-minimalist/style.css',
                'js_path' => 'templates/modern-minimalist/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Romantic Floral',
                'description' => 'Template undangan dengan desain romantis dan floral. Menggunakan warna rose dan cream dengan elemen bunga. Cocok untuk pernikahan dengan tema garden atau outdoor.',
                'thumbnail_path' => 'templates/romantic-floral/thumbnail.svg',
                'html_path' => 'templates/romantic-floral/template.html',
                'css_path' => 'templates/romantic-floral/style.css',
                'js_path' => 'templates/romantic-floral/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Luxury Gold',
                'description' => 'Template undangan mewah dengan aksen emas dan hitam. Desain premium dengan animasi shimmer dan frame emas yang elegan. Cocok untuk pernikahan dengan tema luxury dan glamour.',
                'thumbnail_path' => 'templates/luxury-gold/thumbnail.svg',
                'html_path' => 'templates/luxury-gold/template.html',
                'css_path' => 'templates/luxury-gold/style.css',
                'js_path' => 'templates/luxury-gold/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Rustic Vintage',
                'description' => 'Template undangan dengan tema vintage dan rustic. Menggunakan warna coklat kayu dan krem dengan border klasik. Cocok untuk pernikahan dengan tema barn wedding atau vintage.',
                'thumbnail_path' => 'templates/rustic-vintage/thumbnail.svg',
                'html_path' => 'templates/rustic-vintage/template.html',
                'css_path' => 'templates/rustic-vintage/style.css',
                'js_path' => 'templates/rustic-vintage/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Ocean Breeze',
                'description' => 'Template undangan dengan tema pantai dan laut. Menggunakan gradasi biru dengan animasi gelombang dan efek ripple. Cocok untuk pernikahan dengan tema beach wedding.',
                'thumbnail_path' => 'templates/ocean-breeze/thumbnail.svg',
                'html_path' => 'templates/ocean-breeze/template.html',
                'css_path' => 'templates/ocean-breeze/style.css',
                'js_path' => 'templates/ocean-breeze/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Garden Party',
                'description' => 'Template undangan dengan tema taman dan bunga. Menggunakan warna pink dan peach dengan ilustrasi floral yang indah. Cocok untuk pernikahan dengan tema garden party.',
                'thumbnail_path' => 'templates/garden-party/thumbnail.svg',
                'html_path' => 'templates/garden-party/template.html',
                'css_path' => 'templates/garden-party/style.css',
                'js_path' => 'templates/garden-party/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Night Sky',
                'description' => 'Template undangan dengan tema malam dan bintang. Menggunakan warna gelap dengan efek bintang berkelap-kelip dan shooting stars. Cocok untuk pernikahan dengan tema starry night.',
                'thumbnail_path' => 'templates/night-sky/thumbnail.svg',
                'html_path' => 'templates/night-sky/template.html',
                'css_path' => 'templates/night-sky/style.css',
                'js_path' => 'templates/night-sky/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Parallax Mountain',
                'description' => 'Template dengan efek parallax gunung yang smooth. Menggunakan multiple layers yang bergerak dengan kecepatan berbeda saat scroll. Cocok untuk pernikahan outdoor atau mountain wedding.',
                'thumbnail_path' => 'templates/parallax-mountain/thumbnail.svg',
                'html_path' => 'templates/parallax-mountain/template.html',
                'css_path' => 'templates/parallax-mountain/style.css',
                'js_path' => 'templates/parallax-mountain/script.js',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}
