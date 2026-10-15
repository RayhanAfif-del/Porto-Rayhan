<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Project;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Proyek awal. URL demo/repository sengaja dikosongkan; isi jika sudah ada.
        $projects = [
            [
                'title' => 'SIJAKA',
                'slug' => 'sijaka',
                'description' => 'Sistem Informasi Bursa Kerja Khusus yang membantu menghubungkan alumni dengan peluang kerja dan mitra industri.',
                'category' => 'web',
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Tailwind CSS'],
            ],
            [
                'title' => 'E-Commerce Website',
                'slug' => 'e-commerce-website',
                'description' => 'Website toko online dengan katalog produk dan antarmuka berorientasi pengguna.',
                'category' => 'web',
                'technologies' => ['PHP', 'MySQL', 'JavaScript'],
            ],
            [
                'title' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'description' => 'Rancangan antarmuka dan prototipe untuk meningkatkan kejelasan alur serta pengalaman pengguna.',
                'category' => 'uiux',
                'technologies' => ['Figma'],
            ],
        ];

        foreach ($projects as $i => $project) {
            Project::updateOrCreate(
                ['slug' => $project['slug']],
                $project + ['published' => true, 'sort_order' => $i],
            );
        }

        // Data pendidikan placeholder: ganti nama sekolah dan periode dengan data asli.
        Education::updateOrCreate(
            ['major' => 'Pengembangan Perangkat Lunak dan Gim (PPLG)'],
            [
                'school' => 'Nama Sekolah (isi dengan data asli)',
                'period' => 'Periode (isi dengan data asli)',
                'description' => 'Mempelajari pemrograman web, basis data, dan perancangan antarmuka pengguna.',
                'sort_order' => 0,
            ],
        );

        $this->call(ExperienceSeeder::class);
    }
}
