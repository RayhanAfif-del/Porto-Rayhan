<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SiteSettingSeeder extends Seeder
{
    /**
     * Default settings template.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultSettings(): array
    {
        $defaultServices = [
            [
                'icon' => 'code',
                'title' => 'Web Development',
                'items' => [
                    'Pengembangan website dengan Laravel dan PHP.',
                    'Implementasi antarmuka dengan HTML, CSS, dan JavaScript.',
                    'Pengelolaan database MySQL.',
                ],
            ],
            [
                'icon' => 'pen',
                'title' => 'UI/UX Design',
                'items' => [
                    'Wireframing dan prototyping.',
                    'Perancangan layout website.',
                    'Desain antarmuka menggunakan Figma.',
                ],
            ],
            [
                'icon' => 'devices',
                'title' => 'Responsive Web Design',
                'items' => [
                    'Layout adaptif untuk desktop, tablet, dan mobile.',
                    'Optimalisasi navigasi dan pengalaman pengguna.',
                    'Pengujian tampilan lintas perangkat.',
                ],
            ],
        ];

        $defaultTechnologies = config('portfolio.technologies', [
            'HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel',
            'Tailwind CSS', 'MySQL', 'Figma', 'GitHub',
        ]);

        $defaultGallery = config('portfolio.images.gallery', [
            ['src' => 'images/gallery-1.svg', 'alt' => 'Placeholder dokumentasi proyek 1', 'class' => 'md:col-span-7 aspect-[16/10]'],
            ['src' => 'images/gallery-2.svg', 'alt' => 'Placeholder dokumentasi proyek 2', 'class' => 'md:col-span-5 aspect-[4/5] md:aspect-auto'],
            ['src' => 'images/gallery-3.svg', 'alt' => 'Placeholder dokumentasi proyek 3', 'class' => 'md:col-span-4 aspect-[4/3]'],
            ['src' => 'images/gallery-4.svg', 'alt' => 'Placeholder dokumentasi proyek 4', 'class' => 'md:col-span-8 aspect-[16/9] md:aspect-[16/8]'],
        ]);

        return [
            // Identity
            [
                'key' => 'identity.name',
                'value' => config('portfolio.name', 'Muhammad Rayhan'),
                'type' => 'text',
                'group' => 'identity',
                'label' => 'Nama Lengkap',
                'sort_order' => 1,
            ],
            [
                'key' => 'identity.short_name',
                'value' => config('portfolio.short_name', 'Rayhan'),
                'type' => 'text',
                'group' => 'identity',
                'label' => 'Nama Panggilan',
                'sort_order' => 2,
            ],
            [
                'key' => 'identity.role',
                'value' => config('portfolio.role', 'Junior Web Developer & UI/UX Designer'),
                'type' => 'text',
                'group' => 'identity',
                'label' => 'Peran / Jabatan',
                'sort_order' => 3,
            ],
            [
                'key' => 'identity.bio_summary',
                'value' => 'Membangun pengalaman web yang presisi, performan tinggi, dan estetik modern dengan fokus pada ekosistem web responsif.',
                'type' => 'textarea',
                'group' => 'identity',
                'label' => 'Ringkasan Singkat (Bio Headline)',
                'sort_order' => 4,
            ],

            // Hero
            [
                'key' => 'hero.tagline',
                'value' => 'PERSONAL PORTFOLIO / WEB DEVELOPER',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Tagline Atas',
                'sort_order' => 1,
            ],
            [
                'key' => 'hero.headline',
                'value' => 'Building Digital Experiences That Matter.',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Judul Utama (Headline)',
                'sort_order' => 2,
            ],
            [
                'key' => 'hero.description',
                'value' => 'Saya Muhammad Rayhan, Junior Web Developer & UI/UX Designer yang berfokus pada pengembangan website modern dan perancangan pengalaman digital yang intuitif.',
                'type' => 'textarea',
                'group' => 'hero',
                'label' => 'Deskripsi Singkat Hero',
                'sort_order' => 3,
            ],
            [
                'key' => 'hero.badges',
                'value' => 'Web Development, UI/UX Design, Creative Solutions',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Keahlian di Bawah Hero (Pisahkan koma)',
                'sort_order' => 4,
            ],
            [
                'key' => 'images.hero',
                'value' => config('portfolio.images.hero', 'images/hero-placeholder.svg'),
                'type' => 'image',
                'group' => 'hero',
                'label' => 'Gambar Latar Hero',
                'sort_order' => 5,
            ],

            // About
            [
                'key' => 'about.label',
                'value' => 'WHO I AM',
                'type' => 'text',
                'group' => 'about',
                'label' => 'Label Section',
                'sort_order' => 1,
            ],
            [
                'key' => 'about.headline',
                'value' => 'I build websites and digital experiences that connect design with functionality.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Judul Headline About',
                'sort_order' => 2,
            ],
            [
                'key' => 'about.paragraph_1',
                'value' => 'Saya Muhammad Rayhan, siswa jurusan PPLG (Pengembangan Perangkat Lunak dan Gim) yang tertarik pada Web Development dan UI/UX Design.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Paragraf Pertama',
                'sort_order' => 3,
            ],
            [
                'key' => 'about.paragraph_2',
                'value' => 'Saya terus belajar dengan membangun proyek, mulai dari sistem informasi hingga rancangan antarmuka. Tujuan saya adalah berkembang menjadi developer yang mampu menjembatani desain dan fungsionalitas.',
                'type' => 'textarea',
                'group' => 'about',
                'label' => 'Paragraf Kedua',
                'sort_order' => 4,
            ],
            [
                'key' => 'images.about',
                'value' => config('portfolio.images.about', 'images/about-placeholder.svg'),
                'type' => 'image',
                'group' => 'about',
                'label' => 'Foto Profil About',
                'sort_order' => 5,
            ],

            // Services
            [
                'key' => 'services.label',
                'value' => 'SKILLS & SERVICES',
                'type' => 'text',
                'group' => 'services',
                'label' => 'Label Section',
                'sort_order' => 1,
            ],
            [
                'key' => 'services.headline',
                'value' => 'What I Can Do',
                'type' => 'text',
                'group' => 'services',
                'label' => 'Judul Layanan',
                'sort_order' => 2,
            ],
            [
                'key' => 'services.description',
                'value' => 'Menggabungkan pengembangan web dan desain antarmuka untuk membangun pengalaman digital yang fungsional, responsif, dan menarik.',
                'type' => 'textarea',
                'group' => 'services',
                'label' => 'Deskripsi Layanan',
                'sort_order' => 3,
            ],
            [
                'key' => 'services.items',
                'value' => json_encode($defaultServices, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'type' => 'json',
                'group' => 'services',
                'label' => 'Daftar Layanan & Detail',
                'sort_order' => 4,
            ],

            // Technologies
            [
                'key' => 'technologies.list',
                'value' => json_encode($defaultTechnologies, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'type' => 'json',
                'group' => 'technologies',
                'label' => 'Daftar Teknologi & Tools (Array)',
                'sort_order' => 1,
            ],

            // Social & Contact
            [
                'key' => 'contact.email',
                'value' => config('portfolio.contact.email') ?? '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Email Kontak',
                'sort_order' => 1,
            ],
            [
                'key' => 'contact.github',
                'value' => config('portfolio.contact.github') ?? '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Link Profil GitHub',
                'sort_order' => 2,
            ],
            [
                'key' => 'contact.linkedin',
                'value' => config('portfolio.contact.linkedin') ?? '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Link Profil LinkedIn',
                'sort_order' => 3,
            ],
            [
                'key' => 'contact.whatsapp',
                'value' => config('portfolio.contact.whatsapp') ?? '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Nomor WhatsApp (Contoh: 628123456789)',
                'sort_order' => 4,
            ],
            [
                'key' => 'portfolio.cv_url',
                'value' => config('portfolio.cv_url') ?? '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Link / Path File CV',
                'sort_order' => 5,
            ],

            // Gallery
            [
                'key' => 'images.gallery',
                'value' => json_encode($defaultGallery, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'type' => 'json',
                'group' => 'gallery',
                'label' => 'Daftar Gambar Galeri',
                'sort_order' => 1,
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::defaultSettings() as $item) {
            $setting = SiteSetting::where('key', $item['key'])->first();
            if (! $setting) {
                SiteSetting::create($item);
            } elseif ($setting->value === null || $setting->value === '') {
                $setting->update(['value' => $item['value']]);
            }
        }

        Cache::forget('site_settings_map');
    }
}
