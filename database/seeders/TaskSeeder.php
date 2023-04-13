<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = Office::all();
        $tasks = [
            [
                'name' => 'يوم مكتبي',
            ],
            [
                'name' => 'إجازة',
            ],
            [
                'name' => 'برنامج تدريبي',
            ],
            [
                'name' => 'مكلف بمهمة',
            ],
        ];

        foreach ($offices as $office) {
            foreach($tasks as $task){
                Task::create([
                    'name' => $task['name'],
                    'office_id' => $office->id,
                    'level_id' => 5,
                    'status' => 1,
                ]);
            };
        };
    }
}
