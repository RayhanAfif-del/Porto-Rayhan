<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultExperiences = config('portfolio.experiences', [
            [
                'role' => 'Frontend & Web Developer Intern',
                'company' => 'Digital Agency / Prakerin',
                'period' => '2024 — 2025',
                'description' => 'Mengembangkan antarmuka web responsif, slicing komponen UI/UX dari Figma ke Tailwind CSS & Blade, dan integrasi API RESTful.',
                'skills' => ['Laravel', 'Tailwind CSS', 'Figma', 'JavaScript'],
                'sort_order' => 0,
            ],
            [
                'role' => 'Freelance Web Developer & UI Designer',
                'company' => 'Proyek Independen',
                'period' => '2023 — Sekarang',
                'description' => 'Merancang UI/UX modern dan membangun website sistem informasi serta landing page fungsional untuk berbagai kebutuhan klien.',
                'skills' => ['UI/UX Design', 'PHP', 'MySQL', 'Figma'],
                'sort_order' => 1,
            ],
        ]);

        foreach ($defaultExperiences as $i => $exp) {
            Experience::updateOrCreate(
                [
                    'role' => $exp['role'],
                    'company' => $exp['company'],
                ],
                [
                    'period' => $exp['period'] ?? null,
                    'description' => $exp['description'] ?? null,
                    'skills' => $exp['skills'] ?? [],
                    'sort_order' => $exp['sort_order'] ?? $i,
                ]
            );
        }
    }
}
