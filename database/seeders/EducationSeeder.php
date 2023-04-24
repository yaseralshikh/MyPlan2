<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educations = [
            [
                'name' => 'الإدارة العامة للتعليم بمنطقة جازان',
            ],
            [
                'name' => 'إدارة التعليم بمحافظة صبيا',
            ],
        ];

        foreach ($educations as $education) {
            Education::create([
                'name' => $education['name'],
            ]);
        };
    }
}
