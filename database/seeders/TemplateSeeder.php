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
            [
                'name' => '3D Flip Card',
                'description' => 'Template dengan efek 3D flip card yang interaktif. Card mempelai bisa di-flip untuk melihat detail. Animasi 3D transform yang smooth. Cocok untuk pernikahan modern dan tech-savvy.',
                'thumbnail_path' => 'templates/3d-flip-card/thumbnail.svg',
                'html_path' => 'templates/3d-flip-card/template.html',
                'css_path' => 'templates/3d-flip-card/style.css',
                'js_path' => 'templates/3d-flip-card/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Glassmorphism',
                'description' => 'Template dengan efek glass morphism yang trendy. Background blur dengan transparansi. Efek frosted glass yang elegan. Cocok untuk pernikahan minimalis modern.',
                'thumbnail_path' => 'templates/glassmorphism/thumbnail.svg',
                'html_path' => 'templates/glassmorphism/template.html',
                'css_path' => 'templates/glassmorphism/style.css',
                'js_path' => 'templates/glassmorphism/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Neon Glow',
                'description' => 'Template dengan efek neon glow yang futuristik. Text dan border dengan glow effect. Animasi neon yang berkedip. Cocok untuk pernikahan dengan tema futuristic atau cyberpunk.',
                'thumbnail_path' => 'templates/neon-glow/thumbnail.svg',
                'html_path' => 'templates/neon-glow/template.html',
                'css_path' => 'templates/neon-glow/style.css',
                'js_path' => 'templates/neon-glow/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Watercolor Splash',
                'description' => 'Template dengan efek watercolor splash yang artistik. Background dengan gradasi watercolor. Animasi splash yang lembut. Cocok untuk pernikahan dengan tema artistic atau bohemian.',
                'thumbnail_path' => 'templates/watercolor-splash/thumbnail.svg',
                'html_path' => 'templates/watercolor-splash/template.html',
                'css_path' => 'templates/watercolor-splash/style.css',
                'js_path' => 'templates/watercolor-splash/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Particle Explosion',
                'description' => 'Template dengan efek particle explosion yang dinamis. Particles bergerak dan meledak saat interaksi. Canvas animation yang smooth. Cocok untuk pernikahan yang energetic dan fun.',
                'thumbnail_path' => 'templates/particle-explosion/thumbnail.svg',
                'html_path' => 'templates/particle-explosion/template.html',
                'css_path' => 'templates/particle-explosion/style.css',
                'js_path' => 'templates/particle-explosion/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Scroll Animation',
                'description' => 'Template dengan scroll-triggered animations yang menarik. Setiap section muncul dengan animasi berbeda. Intersection Observer API. Cocok untuk pernikahan yang ingin storytelling yang menarik.',
                'thumbnail_path' => 'templates/scroll-animation/thumbnail.svg',
                'html_path' => 'templates/scroll-animation/template.html',
                'css_path' => 'templates/scroll-animation/style.css',
                'js_path' => 'templates/scroll-animation/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Typewriter Effect',
                'description' => 'Template dengan efek typewriter pada text. Text muncul seperti sedang diketik. Cursor blinking animation. Cocok untuk pernikahan dengan tema vintage atau retro.',
                'thumbnail_path' => 'templates/typewriter-effect/thumbnail.svg',
                'html_path' => 'templates/typewriter-effect/template.html',
                'css_path' => 'templates/typewriter-effect/style.css',
                'js_path' => 'templates/typewriter-effect/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Morphing Shapes',
                'description' => 'Template dengan shapes yang berubah bentuk (morphing). SVG path animation yang smooth. Shapes berubah dari satu bentuk ke bentuk lain. Cocok untuk pernikahan modern dan creative.',
                'thumbnail_path' => 'templates/morphing-shapes/thumbnail.svg',
                'html_path' => 'templates/morphing-shapes/template.html',
                'css_path' => 'templates/morphing-shapes/style.css',
                'js_path' => 'templates/morphing-shapes/script.js',
                'is_active' => true,
            ],
            [
                'name' => 'Cinematic Reveal',
                'description' => 'Template dengan efek cinematic reveal seperti film. Opening dengan curtain effect. Smooth transitions antar section. Cocok untuk pernikahan yang grand dan elegant.',
                'thumbnail_path' => 'templates/cinematic-reveal/thumbnail.svg',
                'html_path' => 'templates/cinematic-reveal/template.html',
                'css_path' => 'templates/cinematic-reveal/style.css',
                'js_path' => 'templates/cinematic-reveal/script.js',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}
