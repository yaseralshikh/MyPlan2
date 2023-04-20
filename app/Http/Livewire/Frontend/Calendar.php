<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Task;
use App\Models\User;
use App\Models\Week;
use App\Models\Event;
use App\Models\Level;
use App\Models\Office;
use Livewire\Component;
use App\Models\Semester;
use App\Rules\UserOverLap;
use App\Rules\EventOverLap;
use App\Rules\DateOutService;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Calendar extends Component
{
    use LivewireAlert;

    public $data = [];
    public $tasks = [];

    public $officeId = null;

    public $all_user;
    public $office_id;
    public $level_id;
    public $task_id;
    public $start;
    public $end;
    public $event_id;
    public $note;

    public $weeks = [];

    protected function rules(): array
    {
        return ([
            'level_id' => ['required'],
            'task_id' => ['required', new EventOverLap($this->start), new UserOverLap($this->start) , new DateOutService($this->start, $this->end)],
        ]);
    }

    public function resetErrorMsg()
    {
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $taskName = Task::whereStatus(true)->where('id', $this->task_id)->pluck('name')->first();

        $color = null;

        switch ($taskName) {
            case 'إجازة':
                $color = '#f5e7fe';
                break;
            case 'يوم مكتبي':
                $color = '#f2f2f2';
                break;
            case 'برنامج تدريبي':
                $color = '#fff7e6';
                break;
            case 'مكلف بمهمة':
                $color = '#e6ffe6';
                break;
            default:
                $color = '#e6f5ff';
        }

        $semester_Id = Semester::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();
        $week_Id = Week::where('start', '<=', $this->start)->where('end', '>=', $this->end)->pluck('id')->first();

        if ($this->all_user) {

            $users = User::where('office_id', auth()->user()->office_id)->whereStatus(1)->get();

            foreach ($users as $user) {
                Event::create([
                    'user_id' => $user->id,
                    'office_id' => $user->office_id,
                    'semester_id' => $semester_Id,
                    'week_id' => $week_Id,
                    'task_id' => $this->task_id,
                    'note' => $this->note,
                    'start' => $this->start,
                    'end' => $this->end,
                    'color' => $color,
                    'status' => 1,
                ]);
            }
        } else {

            Event::create([
                'user_id' => auth()->user()->id,
                'office_id' => auth()->user()->office_id,
                'semester_id' => $semester_Id,
                'week_id' => $week_Id,
                'task_id' => $this->task_id,
                'note' => $this->note,
                'start' => $this->start,
                'end' => $this->end,
                'color' => $color,
            ]);
        }

        $this->reset();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('closeModalCreate', ['close' => true]);
        $this->dispatchBrowserEvent('refreshEventCalendar', ['refresh' => true]);
        $this->dispatchBrowserEvent('swal', [
            'title' => __('site.saveSuccessfully'),
            'timer' => 2000,
            'timerProgressBar' => true,
            'icon' => 'success',
            'showConfirmButton' => false,
            'toast' => true,
            'position' => 'center',
        ]);
    }

    public function updated()
    {
        $this->getTaskesData();
    }

    public function OfficeOption($id)
    {
        $this->officeId = $id;
    }

    public function LevelOption()
    {
        $this->getTaskesData();
    }

    public function getTaskesData()
    {
        $officeId = $this->office_id ? $this->office_id : auth()->user()->office_id;

        $this->tasks = Task::where('office_id', $officeId)
        ->whereStatus(1)->where('level_id' , $this->level_id)
        ->orderBy('level_id', 'asc')
        ->orderBy('name', 'asc')
        ->get();
    }

    public function render()
    {
        if (auth()->user()->gender == 1) {
            $office_gender = [1,2,3,4,5,6,auth()->user()->office->id];
        } else {
            $office_gender = [7,8,9,10,12,auth()->user()->office->id];
        }

        $offices = Office::whereStatus(true)->whereIn('id', $office_gender)->get();

        $levels = Level::with('tasks')->get();

        $tasks = $this->getTaskesData();


        return view('livewire.frontend.calendar', compact(
            'offices',
            'levels',
            'tasks',
        ));
    }
}

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

        // $this->dispatchBrowserEvent('swal', [
        //     'title' => 'هل تريد الاستمرار؟',
        //     'timer' => 2000,
        //     'timerProgressBar' => true,
        //     'icon' => 'success',
        //     'toast' => true,
        //     'showConfirmButton' => false,
        //     'position' => 'top-end',
        // ]);
