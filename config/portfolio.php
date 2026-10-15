<?php

/*
|--------------------------------------------------------------------------
| Data profil portofolio
|--------------------------------------------------------------------------
| Semua data pribadi yang belum tersedia dibiarkan kosong (null). Section
| terkait akan menampilkan placeholder atau menyembunyikan tautan, sehingga
| tidak ada tautan palsu. Isi lewat file .env atau ubah langsung di sini.
*/

return [
    'name' => 'Muhammad Rayhan',
    'short_name' => 'Rayhan',
    'role' => 'Junior Web Developer & UI/UX Designer',

    'contact' => [
        'email' => env('PORTFOLIO_EMAIL'),
        'github' => env('PORTFOLIO_GITHUB'),
        'linkedin' => env('PORTFOLIO_LINKEDIN'),
        // Nomor internasional tanpa tanda +, contoh: 628123456789
        'whatsapp' => env('PORTFOLIO_WHATSAPP'),
    ],

    // URL file CV (mis. /files/cv-rayhan.pdf). Kosongkan jika belum ada.
    'cv_url' => env('PORTFOLIO_CV_URL'),

    'technologies' => [
        'HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel',
        'Tailwind CSS', 'MySQL', 'Figma', 'GitHub',
    ],

    // Semua gambar berupa placeholder SVG lokal. Ganti dengan foto asli
    // (letakkan di public/images lalu ubah path di sini).
    'images' => [
        'hero' => 'images/hero-placeholder.svg',
        'about' => 'images/about-placeholder.svg',
        'gallery' => [
            ['src' => 'images/gallery-1.svg', 'alt' => 'Placeholder dokumentasi proyek 1', 'class' => 'md:col-span-7 aspect-[16/10]'],
            ['src' => 'images/gallery-2.svg', 'alt' => 'Placeholder dokumentasi proyek 2', 'class' => 'md:col-span-5 aspect-[4/5] md:aspect-auto'],
            ['src' => 'images/gallery-3.svg', 'alt' => 'Placeholder dokumentasi proyek 3', 'class' => 'md:col-span-4 aspect-[4/3]'],
            ['src' => 'images/gallery-4.svg', 'alt' => 'Placeholder dokumentasi proyek 4', 'class' => 'md:col-span-8 aspect-[16/9] md:aspect-[16/8]'],
        ],
    ],

    // Daftar pengalaman kerja, praktik lapangan (PKL/Prakerin), atau freelance
    'experiences' => [
        [
            'role' => 'Frontend & Web Developer Intern',
            'company' => 'Digital Agency / Prakerin',
            'period' => '2024 — 2025',
            'description' => 'Mengembangkan antarmuka web responsif, slicing komponen UI/UX dari Figma ke Tailwind CSS & Blade, dan integrasi API RESTful.',
            'skills' => ['Laravel', 'Tailwind CSS', 'Figma', 'JavaScript'],
        ],
        [
            'role' => 'Freelance Web Developer & UI Designer',
            'company' => 'Proyek Independen',
            'period' => '2023 — Sekarang',
            'description' => 'Merancang UI/UX modern dan membangun website sistem informasi serta landing page fungsional untuk berbagai kebutuhan klien.',
            'skills' => ['UI/UX Design', 'PHP', 'MySQL', 'Figma'],
        ],
    ],
];
