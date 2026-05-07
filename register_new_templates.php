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
];

foreach ($newTemplates as $data) {
    Template::updateOrCreate(['name' => $data['name']], $data);
    echo "Registered: " . $data['name'] . PHP_EOL;
}
