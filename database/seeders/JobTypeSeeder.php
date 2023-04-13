<?php

namespace Database\Seeders;

use App\Models\JobType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            'مشرف تربوي',
            'تقنية المعلومات',
            'مساعد مدير المكتب للشؤون التعليمية',
            'مساعد مدير المكتب للشؤون المدرسية',
            'مدير مكتب التعليم',
            'مدير مكتب الإشراف',
            'المساعد للشؤون التعليمة',
            'المساعد للشؤون المدرسية',
            'مدير ادارة',
            'إداري',
            'المدير العام للتعليم',
        ];

        foreach ($jobs as $job) {
            JobType::create([
                'name' => $job
            ]);
        }
    }
}
