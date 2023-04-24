<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices1 = [
            [
                'name'         => 'مكتب التعليم بوسط جازان - بنين',
                'director'     => 'عبدالرحمن بن عسيري عكور',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بأبي عريش - بنين',
                'director'     => 'الدكتور حسن بن أبكر خضي',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بصامطة - بنين',
                'director'     => 'عبدالرزاق بن محمد الصميلي',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بالمسارحة والحرث - بنين',
                'director'     => 'الدكتور علي بن محمد عطيف',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بالعارضة - بنين',
                'director'     => 'الدكتور إبراهيم محزري',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بفرسان - بنين',
                'director'     => 'عبدالله بن محمد نسيب',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بوسط جازان - بنات',
                'director'     => 'وردة علي بركات',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بأبي عريش - بنات',
                'director'     => 'الدكتور حسن بن أبكر خضي',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بصامطة - بنات',
                'director'     => 'عبدالرزاق بن محمد الصميلي',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بالمسارحة والحرث - بنات',
                'director'     => 'الدكتور علي بن محمد عطيف',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بالعارضة - بنات',
                'director'     => 'الدكتور إبراهيم محزري',
                'education_id' => 1,
            ],
            [
                'name'         => 'مكتب التعليم بفرسان - بنات',
                'director'     => 'عبدالله بن محمد نسيب',
                'education_id' => 1,
            ],
            [
                'name'         => 'إدارة الإشراف التربوي - بنين',
                'director'     => 'د. أحمد بن ظافر عطيف',
                'education_id' => 1,
            ],
        ];

        $offices2 = [
            [
                'name'         => 'مكتب التعليم بصبيا - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بالعيدابي - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بفيفاء - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بالداير - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بالدرب - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم ببيش - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب تعليم هروب - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب تعليم ضمد - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب تعليم الريث - بنين',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بصبيا - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بالعيدابي - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بفيفاء - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بالداير - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم بالدرب - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب التعليم ببيش - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب تعليم هروب - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب تعليم ضمد - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
            [
                'name'         => 'مكتب تعليم الريث - بنات',
                'director'     => 'some one',
                'education_id' => 2,
            ],
        ];

        foreach (array_merge($offices1, $offices2) as $office) {
            Office::create([
                'name'          => $office['name'],
                'director'      => $office['director'],
                'education_id'  => $office['education_id'],
            ]);
        };
    }
}
