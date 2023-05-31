<?php

namespace App\Http\Livewire\Backend\SubTasks;

use Livewire\Component;

class SubTasks extends Component
{
    public function render()
    {
        return view('livewire.backend.sub-tasks.sub-tasks')->layout('layouts.admin');
    }
}
