<?php

namespace Database\Seeders;

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
        $offices = [
            [
                'name'         => 'مكتب التعليم بوسط جازان - بنين',
                'director'     => 'عبدالرحمن بن عسيري عكور',
            ],
            [
                'name'         => 'مكتب التعليم بأبي عريش - بنين',
                'director'     => 'الدكتور حسن بن أبكر خضي',
            ],
            [
                'name'         => 'مكتب التعليم بصامطة - بنين',
                'director'     => 'عبدالرزاق بن محمد الصميلي',
            ],
            [
                'name'         => 'مكتب التعليم بالمسارحة والحرث - بنين',
                'director'     => 'الدكتور علي بن محمد عطيف',
            ],
            [
                'name'         => 'مكتب التعليم بالعارضة - بنين',
                'director'     => 'الدكتور إبراهيم محزري',
            ],
            [
                'name'         => 'مكتب التعليم بفرسان - بنين',
                'director'     => 'عبدالله بن محمد نسيب',
            ],
            [
                'name'         => 'مكتب التعليم بوسط جازان - بنات',
                'director'     => 'وردة علي بركات',
            ],
            [
                'name'         => 'مكتب التعليم بأبي عريش - بنات',
                'director'     => 'الدكتور حسن بن أبكر خضي',
            ],
            [
                'name'         => 'مكتب التعليم بصامطة - بنات',
                'director'     => 'عبدالرزاق بن محمد الصميلي',
            ],
            [
                'name'         => 'مكتب التعليم بالمسارحة والحرث - بنات',
                'director'     => 'الدكتور علي بن محمد عطيف',
            ],
            [
                'name'         => 'مكتب التعليم بالعارضة - بنات',
                'director'     => 'الدكتور إبراهيم محزري',
            ],
            [
                'name'         => 'مكتب التعليم بفرسان - بنات',
                'director'     => 'عبدالله بن محمد نسيب',
            ],
            [
                'name'         => 'إدارة الإشراف التربوي - بنين',
                'director'     => 'د. أحمد بن ظافر عطيف',
            ],
        ];

        foreach ($offices as $office) {
            Office::create([
                'name' => $office['name'],
                'director' => $office['director'],
            ]);
        };
    }
}
