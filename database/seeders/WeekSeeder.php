<?php

namespace Database\Seeders;

use App\Models\Week;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weeks = [
            [
                'name'          => 'ف3 - الأسبوع الأول',
                'start'         => '2023-03-12',
                'end'           => '2023-03-16' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الثاني',
                'start'         => '2023-03-19',
                'end'           => '2023-03-23' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الثالث',
                'start'         => '2023-03-26',
                'end'           => '2023-03-30' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الرابع',
                'start'         => '2023-04-02',
                'end'           => '2023-04-06' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الخامس',
                'start'         => '2023-04-09',
                'end'           => '2023-04-13' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع السادس',
                'start'         => '2023-04-23',
                'end'           => '2023-04-27' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع السابع',
                'start'         => '2023-04-30',
                'end'           => '2023-05-04' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الثامن',
                'start'         => '2023-05-07',
                'end'           => '2023-05-11' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع التاسع',
                'start'         => '2023-05-14',
                'end'           => '2023-05-18' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع العاشر',
                'start'         => '2023-05-21',
                'end'           => '2023-05-25' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الحادي عشر',
                'start'         => '2023-05-28',
                'end'           => '2023-06-01' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الثاني عشر',
                'start'         => '2023-06-04',
                'end'           => '2023-06-08' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الثالث عشر',
                'start'         => '2023-06-11',
                'end'           => '2023-06-15' ,
            ],
            [
                'name'          => 'ف3 - الأسبوع الرابع عشر',
                'start'         => '2023-06-18',
                'end'           => '2023-06-22' ,
            ],
        ];

        foreach ($weeks as $index => $week) {
            Week::create([
                'name'          => $week['name'],
                'start'         => $week['start'] ,
                'end'           => $week['end'] ,
                'semester_id'   => 2,
                'status'        => 1
            ]);
        }
    }
}
