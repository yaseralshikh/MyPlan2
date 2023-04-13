<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semesters = [
            [
                'name'         => 'الفصل الدراسي الأول',
                'start'         => '2022-08-28',
                'end'           => '2022-11-24' ,
            ],
            [
                'name'         => 'الفصل الدراسي الثاني',
                'start'         => '2022-12-04',
                'end'           => '2023-03-02' ,
            ],
            [
                'name'         => 'الفصل الدراسي الثالث',
                'start'         => '2023-03-12',
                'end'           => '2023-06-22' ,
            ],
        ];

        foreach ($semesters as $index => $semester) {
            Semester::create([
                'name'         => $semester['name'],
                'start'         => $semester['start'] ,
                'end'           => $semester['end'] ,
                'school_year'   => 1444,
                'active'        => $index == 3 ? 1 : 0 ,
                'status'        => 1
            ]);
        }
    }
}
