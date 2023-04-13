<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            'إدارة مدرسية',
            'تربية إسلامية',
            'لغة عربية',
            'صفوف أولية',
            'رياضيات',
            'علوم',
            'كيمياء',
            'فيزياء',
            'أحياء',
            'لغة إنجليزية',
            'اجتماعيات',
            'فنية',
            'بدنية',
            'حاسب آلي',
            'النشاط الطلابي',
            'التوجيه والإرشاد',
            'الموهوبين',
            'التجهيزات المدرسية',
            'الصحة المدرسية',
            'الجودة',
            'تقنية المعلومات',
            'الاختبارات',
            'التدريب التربوي',
        ];

        foreach ($specializations as $specialization) {
            Specialization::create([
                'name' => $specialization
            ]);
        }
    }
}
