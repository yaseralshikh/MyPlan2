<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [ 'رياض الأطفال', 'ابتدائي' ,'متوسط' ,'ثانوي' , 'التعليم المستمر', 'مجمع' , 'أخرى'];

        foreach ($levels as $level) {
            Level::create([
                'name' => $level
            ]);
        }
    }
}
