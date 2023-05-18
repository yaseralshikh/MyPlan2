<?php

namespace Database\Seeders;

use App\Models\Subtask;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubtaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subtasks = [
            [
                'name'              => 'القيام بالمهام والمسؤوليات والأدوار في الدليل الإرشادي للمشرف التربوي الأخير.',
                'section'           => 'مهمة فرعية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 0,
            ],
            [
                'name'              => 'دارسة أسئلة اختبارات الفصل الدراسي السابق ومناقشتها مع المعلم أثناء الزيارة الفنية. ومطابقتها للمواصفات والمقاييس الخاصة بها.',
                'section'           => 'مهمة فرعية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 1,
            ],
            [
                'name'              => 'الاطلاع على أعمال المعلم/ين المسندين ومتابعتهم من مدير المدرسة بالمنصة ونور.',
                'section'           => 'مهمة فرعية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 2,
            ],
            [
                'name'              => 'الاطلاع على إجراءات مدير المدرسة المنفذة لتدريب الطلاب في اختبارات نافس للصفوف الثالث والسادس الابتدائي والثالث المتوسط كما خطط له من قبل الإدارة العامة.',
                'section'           => 'مهمة فرعية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 3,
            ],
            [
                'name'              => 'ص : متابعة الدوام.',
                'section'           => 'حاشية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 0,
            ],
            [
                'name'              => 'ص : مكتب سعادة مدير التعليم.',
                'section'           => 'حاشية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 1,
            ],
            [
                'name'              => '---------------------',
                'section'           => 'حاشية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 2,
            ],
            [
                'name'              => 'سيتم اعتماد الخطط في نظام نور في تمام الساعة 7:45 من صباح كل يوم أحد من كل أسبوع.',
                'section'           => 'حاشية',
                'office_id'         => '1',
                'section_type_id'   => 1,
                'position'          => 3,
            ],
        ];

        foreach ($subtasks as $subtask) {
            Subtask::create([
                'name'              => $subtask['name'],
                'section'           => $subtask['section'],
                'office_id'         => $subtask['office_id'],
                'section_type_id'   => $subtask['section_type_id'],
                'position'          => $subtask['position'],
            ]);
        };
    }
}
