<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Sosupp\Questionable\Models\Year;
use Sosupp\Questionable\Models\Subject;
use Sosupp\Questionable\Models\AcademicLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcademicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Subjects
        // Subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'English Language', 'code' => 'ENG'],
            ['name' => 'Physics', 'code' => 'PHY'],
            ['name' => 'Chemistry', 'code' => 'CHEM'],
            ['name' => 'Biology', 'code' => 'BIO'],
            ['name' => 'History', 'code' => 'HIST'],
            ['name' => 'Geography', 'code' => 'GEO'],
            ['name' => 'Computer Science', 'code' => 'COMP'],
            ['name' => 'Economics', 'code' => 'ECON'],
            ['name' => 'Business Studies', 'code' => 'BUS']
        ];
        
        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
        
        // Academic Levels
        $levels = [
            ['name' => 'Primary 1', 'code' => 'P1', 'order' => 1],
            ['name' => 'Primary 2', 'code' => 'P2', 'order' => 2],
            ['name' => 'Primary 3', 'code' => 'P3', 'order' => 3],
            ['name' => 'Primary 4', 'code' => 'P4', 'order' => 4],
            ['name' => 'Primary 5', 'code' => 'P5', 'order' => 5],
            ['name' => 'Primary 6', 'code' => 'P6', 'order' => 6],
            ['name' => 'JSS 1', 'code' => 'J1', 'order' => 7],
            ['name' => 'JSS 2', 'code' => 'J2', 'order' => 8],
            ['name' => 'JSS 3', 'code' => 'J3', 'order' => 9],
            ['name' => 'SSS 1', 'code' => 'S1', 'order' => 10],
            ['name' => 'SSS 2', 'code' => 'S2', 'order' => 11],
            ['name' => 'SSS 3', 'code' => 'S3', 'order' => 12],
            ['name' => 'Undergraduate', 'code' => 'UG', 'order' => 13],
            ['name' => 'Postgraduate', 'code' => 'PG', 'order' => 14],
        ];

        foreach ($levels as $level) {
            AcademicLevel::create($level);
        }
        
        // Years
        $currentYear = date('Y');
        Year::create([
            'name' => $currentYear,
            'start_year' => $currentYear,
            'is_current' => true
        ]);
        
        for ($i = 1; $i <= 5; $i++) {
            $year = $currentYear - $i;
            Year::create([
                'name' => $year,
                'start_year' => $year
            ]);
        }
    }
    
}
