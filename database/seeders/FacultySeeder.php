<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\University;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            'جامعة القاهرة' => ['هندسة', 'طب', 'علوم', 'تجارة', 'آداب'],
            'جامعة عين شمس' => ['حاسبات', 'حقوق', 'صيدلة', 'تربية', 'طب'],
            'جامعة الإسكندرية' => ['طب بيطري', 'زراعة', 'تربية رياضية', 'فن جميله'],
            'جامعة المنصورة' => ['هندسة', 'طب أسنان', 'علوم', 'تمريض'],
            'جامعة الزقازيق' => ['آداب', 'تجارة', 'حاسبات', 'حقوق'],
        ];

        foreach ($faculties as $universityName => $facultyList) {
            $university = University::where('name', $universityName)->first();
            if ($university) {
                foreach ($facultyList as $faculty) {
                    Faculty::create([
                        'name' => $faculty,
                        'university_id' => $university->id
                    ]);
                }
            }
        }
    }
}
