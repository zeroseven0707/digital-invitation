<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Template;

$newTemplates = [
    [
        'name' => 'Royal Navy Gold',
        'description' => 'Desain mewah dengan perpaduan warna Navy dan Emas yang elegan serta line-art artistik.',
        'thumbnail_path' => 'templates/royal-navy-gold/thumbnail.png',
        'html_path' => 'templates/royal-navy-gold/template.html',
        'css_path' => 'templates/royal-navy-gold/style.css',
        'js_path' => 'templates/royal-navy-gold/script.js',
        'is_active' => true,
    ],
    [
        'name' => 'Botanical Watercolor',
        'description' => 'Tema bunga watercolor yang lembut dan romantis dengan bingkai Arch yang estetik.',
        'thumbnail_path' => 'templates/botanical-watercolor/thumbnail.png',
        'html_path' => 'templates/botanical-watercolor/template.html',
        'css_path' => 'templates/botanical-watercolor/style.css',
        'js_path' => 'templates/botanical-watercolor/script.js',
        'is_active' => true,
    ],
    [
        'name' => 'Luxury Marble',
        'description' => 'Estetika marmer hitam yang mewah dikombinasikan dengan aksen geometris emas yang modern.',
        'thumbnail_path' => 'templates/luxury-marble/thumbnail.png',
        'html_path' => 'templates/luxury-marble/template.html',
        'css_path' => 'templates/luxury-marble/style.css',
        'js_path' => 'templates/luxury-marble/script.js',
        'is_active' => true,
    ],
    [
        'name' => 'Modern Glassmorphism',
        'description' => 'Desain modern futuristik menggunakan efek glassmorphism transparan dan warna pastel lembut.',
        'thumbnail_path' => 'templates/modern-glassmorphism/thumbnail.svg',
        'html_path' => 'templates/modern-glassmorphism/template.html',
        'css_path' => 'templates/modern-glassmorphism/style.css',
        'js_path' => 'templates/modern-glassmorphism/script.js',
        'is_active' => true,
    ],
    [
        'name' => 'Traditional Bali',
        'description' => 'Desain adat Bali megah dengan ornamen ukiran khas Bali emas dan latar belakang merah bata sakral.',
        'thumbnail_path' => 'templates/traditional-bali/thumbnail.svg',
        'html_path' => 'templates/traditional-bali/template.html',
        'css_path' => 'templates/traditional-bali/style.css',
        'js_path' => null,
        'is_active' => true,
    ],
    [
        'name' => 'Traditional Batik Modern',
        'description' => 'Desain batik modern elegan menggabungkan pola mega mendung kawung dengan aksen emas minimalis.',
        'thumbnail_path' => 'templates/traditional-batik-modern/thumbnail.svg',
        'html_path' => 'templates/traditional-batik-modern/template.html',
        'css_path' => 'templates/traditional-batik-modern/style.css',
        'js_path' => null,
        'is_active' => true,
    ],
    [
        'name' => 'Traditional Jawa',
        'description' => 'Undangan adat Jawa kental dengan latar warna hijau soga dan motif gunungan wayang sakral.',
        'thumbnail_path' => 'templates/traditional-jawa/thumbnail.svg',
        'html_path' => 'templates/traditional-jawa/template.html',
        'css_path' => 'templates/traditional-jawa/style.css',
        'js_path' => null,
        'is_active' => true,
    ],
    [
        'name' => 'Traditional Minang',
        'description' => 'Undangan pernikahan adat Minangkabau dengan hiasan atap rumah gadang emas dan songket mewah.',
        'thumbnail_path' => 'templates/traditional-minang/thumbnail.svg',
        'html_path' => 'templates/traditional-minang/template.html',
        'css_path' => 'templates/traditional-minang/style.css',
        'js_path' => null,
        'is_active' => true,
    ],
    [
        'name' => 'Traditional Sunda',
        'description' => 'Undangan adat Sunda romantis bersahaja dengan aksen siger emas dan melati anggun.',
        'thumbnail_path' => 'templates/traditional-sunda/thumbnail.svg',
        'html_path' => 'templates/traditional-sunda/template.html',
        'css_path' => 'templates/traditional-sunda/style.css',
        'js_path' => null,
        'is_active' => true,
    ],
];

foreach ($newTemplates as $data) {
    Template::updateOrCreate(['name' => $data['name']], $data);
    echo "Registered/Updated: " . $data['name'] . PHP_EOL;
}

echo "All premium and traditional templates registered successfully!" . PHP_EOL;
