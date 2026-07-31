<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\CareerSkill;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    /**
     * Urutan career sesuai kesepakatan:
     * 1. Full Stack Developer
     * 2. Backend Developer
     * 3. UI/UX Designer
     * 4. DevOps Engineer
     * 5. Data Analyst
     *
     * Idempotent: pakai updateOrCreate berdasarkan `name`, jadi aman
     * dijalankan berkali-kali (baik lewat --class=CareerSeeder maupun
     * db:seed penuh) tanpa membuat career/career_skill duplikat.
     */
    public function run(): void
    {
        $careers = [
            [
                'name' => 'Full Stack Developer',
                'icon' => '🚀',
                'description' => 'Kuasai pengembangan frontend dan backend',
                'order' => 1,
                'skills' => [
                    // core
                    ['skill_name' => 'HTML', 'category' => 'core', 'industry_requirement' => 4.2],
                    ['skill_name' => 'CSS', 'category' => 'core', 'industry_requirement' => 4.2],
                    ['skill_name' => 'JavaScript', 'category' => 'core', 'industry_requirement' => 4.5],
                    ['skill_name' => 'PHP', 'category' => 'core', 'industry_requirement' => 4.0],
                    ['skill_name' => 'SQL', 'category' => 'core', 'industry_requirement' => 4.0],
                    // tools
                    ['skill_name' => 'Git & Version Control', 'category' => 'tools', 'industry_requirement' => 4.3],
                    ['skill_name' => 'Testing & Debugging', 'category' => 'tools', 'industry_requirement' => 4.0],
                    // soft skills
                    ['skill_name' => 'Problem Solving', 'category' => 'soft_skills', 'industry_requirement' => 4.5],
                    ['skill_name' => 'Communication', 'category' => 'soft_skills', 'industry_requirement' => 4.0],
                ],
            ],
            [
                'name' => 'Backend Developer',
                'icon' => '⚙️',
                'description' => 'Buat logika sisi server dan sistem basis data',
                'order' => 2,
                'skills' => [
                    ['skill_name' => 'Node.js', 'category' => 'core', 'industry_requirement' => 4.3],
                    ['skill_name' => 'Databases (SQL/NoSQL)', 'category' => 'core', 'industry_requirement' => 4.5],
                    ['skill_name' => 'REST APIs', 'category' => 'core', 'industry_requirement' => 4.5],
                    ['skill_name' => 'Authentication & Security', 'category' => 'core', 'industry_requirement' => 4.2],
                    ['skill_name' => 'Git & Version Control', 'category' => 'tools', 'industry_requirement' => 4.3],
                    ['skill_name' => 'Testing & Debugging', 'category' => 'tools', 'industry_requirement' => 4.2],
                    ['skill_name' => 'Problem Solving', 'category' => 'soft_skills', 'industry_requirement' => 4.6],
                    ['skill_name' => 'Communication', 'category' => 'soft_skills', 'industry_requirement' => 3.8],
                ],
            ],
            [
                'name' => 'UI/UX Designer',
                'icon' => '🎨',
                'description' => 'Merancang pengalaman pengguna yang intuitif dan menarik',
                'order' => 3,
                'skills' => [
                    ['skill_name' => 'HTML', 'category' => 'core', 'industry_requirement' => 3.5],
                    ['skill_name' => 'CSS', 'category' => 'core', 'industry_requirement' => 3.8],
                    ['skill_name' => 'JavaScript', 'category' => 'core', 'industry_requirement' => 3.0],
                    ['skill_name' => 'Wireframing & Prototyping', 'category' => 'core', 'industry_requirement' => 4.5],
                    ['skill_name' => 'User Research', 'category' => 'core', 'industry_requirement' => 4.3],
                    ['skill_name' => 'Git & Version Control', 'category' => 'tools', 'industry_requirement' => 3.2],
                    ['skill_name' => 'Testing & Debugging', 'category' => 'tools', 'industry_requirement' => 3.5],
                    ['skill_name' => 'Problem Solving', 'category' => 'soft_skills', 'industry_requirement' => 4.3],
                    ['skill_name' => 'Communication', 'category' => 'soft_skills', 'industry_requirement' => 4.5],
                ],
            ],
            [
                'name' => 'DevOps Engineer',
                'icon' => '🐳',
                'description' => 'Kelola infrastruktur, otomasi deployment, dan reliability sistem',
                'order' => 4,
                'skills' => [
                    ['skill_name' => 'Docker', 'category' => 'core', 'industry_requirement' => 4.5],
                    ['skill_name' => 'Kubernetes', 'category' => 'core', 'industry_requirement' => 4.3],
                    ['skill_name' => 'CI/CD', 'category' => 'core', 'industry_requirement' => 4.4],
                    ['skill_name' => 'Linux', 'category' => 'core', 'industry_requirement' => 4.4],
                    ['skill_name' => 'Monitoring & Logging', 'category' => 'core', 'industry_requirement' => 4.0],
                    ['skill_name' => 'Git & Version Control', 'category' => 'tools', 'industry_requirement' => 4.5],
                    ['skill_name' => 'Testing & Debugging', 'category' => 'tools', 'industry_requirement' => 3.8],
                    ['skill_name' => 'Problem Solving', 'category' => 'soft_skills', 'industry_requirement' => 4.6],
                    ['skill_name' => 'Communication', 'category' => 'soft_skills', 'industry_requirement' => 4.0],
                ],
            ],
            [
                'name' => 'Data Analyst',
                'icon' => '📊',
                'description' => 'Ubah data menjadi wawasan yang dapat ditindaklanjuti',
                'order' => 5,
                'skills' => [
                    ['skill_name' => 'Python', 'category' => 'core', 'industry_requirement' => 4.3],
                    ['skill_name' => 'SQL', 'category' => 'core', 'industry_requirement' => 4.5],
                    ['skill_name' => 'R', 'category' => 'core', 'industry_requirement' => 3.5],
                    ['skill_name' => 'Data Visualization', 'category' => 'core', 'industry_requirement' => 4.2],
                    ['skill_name' => 'Git & Version Control', 'category' => 'tools', 'industry_requirement' => 3.5],
                    ['skill_name' => 'Testing & Debugging', 'category' => 'tools', 'industry_requirement' => 3.5],
                    ['skill_name' => 'Problem Solving', 'category' => 'soft_skills', 'industry_requirement' => 4.5],
                    ['skill_name' => 'Communication', 'category' => 'soft_skills', 'industry_requirement' => 4.2],
                ],
            ],
        ];

        foreach ($careers as $careerData) {
            $skills = $careerData['skills'];
            unset($careerData['skills']);
            $name = $careerData['name'];

            // updateOrCreate berdasarkan `name` — re-run seeder ini tidak
            // akan membuat career duplikat lagi, cuma update kolomnya kalau
            // ada perubahan (icon/description/order).
            $career = Career::updateOrCreate(
                ['name' => $name],
                $careerData
            );

            foreach ($skills as $index => $skill) {
                // updateOrCreate berdasarkan (career_id, skill_name), supaya
                // re-run juga tidak menduplikasi career_skills.
                CareerSkill::updateOrCreate(
                    [
                        'career_id' => $career->id,
                        'skill_name' => $skill['skill_name'],
                    ],
                    [
                        'category' => $skill['category'],
                        'industry_requirement' => $skill['industry_requirement'],
                        'order' => $index + 1,
                    ]
                );
            }
        }
    }
}