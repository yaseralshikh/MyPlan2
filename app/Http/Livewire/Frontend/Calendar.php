<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Task;
use App\Models\Event;
use Livewire\Component;

class Calendar extends Component
{
    public function render()
    {
        // Retrieve task name, need_care, and count
        // $taskData = Task::selectRaw('name, need_care, count(*) as count')
        // ->groupBy('name', 'need_care')
        // ->get();

        // $title=[];
        // $need_care=[];
        // $count=[];

        // foreach ($taskData as $task) {
        //     array_push($title, $task->name);
        //     array_push($need_care, $task->need_care ? 'red' : '');
        //     array_push($count, $task->count);
        // }

        // dd($title,$need_care,$count);

        return view('livewire.frontend.calendar');
    }
}
