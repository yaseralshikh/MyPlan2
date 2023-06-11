<?php

namespace App\Http\Livewire\Backend\Tasks;

use App\Models\Task;
use App\Models\Level;
use App\Models\Office;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Tasks extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $levels = [];
    public $task;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $byOffice = null; //filter by office_id
    public $byLevel = null; //filter by Level_id

    public $showEditModal = false;

    public $taskIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteTasks'];

    // change need_care

    public function changeNeedCare($taskId)
    {
        $task =Task::findOrFail($taskId);

        if ($task->need_care) {
            $task->update(['need_care' => 0]);
        } else {
            $task->update(['need_care' => 1]);
        }

        $this->alert('success', __('site.saveSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  1500,
            'timerProgressBar' => true,
            'toast'  =>  true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);
    }

    // Updated Select Page Rows

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->tasks->pluck('id')->map(function ($id) {
                return (string) $id;
            });
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    // Reset Selected Rows

    public function resetSelectedRows()
    {
        $this->reset(['selectedRows', 'selectPageRows']);
    }

    // show Sweetalert Confirmation for Delete

    public function deleteSelectedRows()
    {
        $this->dispatchBrowserEvent('show-delete-alert-confirmation');
    }

    // set All selected User As Active

    public function setAllAsActive()
	{
		Task::whereIn('id', $this->selectedRows)->update(['status' => 1]);

        $this->alert('success', __('site.activeSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  2000,
            'toast'  =>  true,
            'timerProgressBar' => true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);

		$this->reset(['selectPageRows', 'selectedRows']);
	}

    // set All selected User As InActive

	public function setAllAsInActive()
	{
		Task::whereIn('id', $this->selectedRows)->update(['status' => 0]);

        $this->alert('success', __('site.inActiveSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  2000,
            'timerProgressBar' => true,
            'toast'  =>  true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);

		$this->reset(['selectPageRows', 'selectedRows']);
	}

    // Delete Selected User with relationship roles And permission

    public function deleteTasks()
    {
        // delete selected users from database
		Task::whereIn('id', $this->selectedRows)->delete();

        $this->alert('success', __('site.deleteSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  2000,
            'timerProgressBar' => true,
            'toast'  =>  true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);

		$this->reset();
    }

    // Updated Search Term
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // show add new user form modal

    public function addNewTask()
    {
        $this->reset('data');
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-form');
    }

    // Create new user

    public function createTask()
    {
        $validatedData = Validator::make($this->data, [
			'name'                  => 'required',
			'office_id'              => 'nullable',
			'level_id'              => 'required',
		])->validate();

        if(empty($validatedData['office_id'])) {
            $validatedData['office_id'] = auth()->user()->office_id;
        }

		Task::create($validatedData);

        $this->dispatchBrowserEvent('hide-form');

        $this->alert('success', __('site.saveSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  2000,
            'timerProgressBar' => true,
            'toast'  =>  true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);
    }

    // show Update new user form modal

    public function edit(Task $task)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->task = $task;

        $this->data = $task->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    // Update Task

    public function updateTask()
    {
        try {
            $validatedData = Validator::make($this->data, [
                'name'            => 'required',
                'office_id'       => 'nullable',
                'level_id'        => 'required',
            ])->validate();

            $this->task->update($validatedData);

            $this->dispatchBrowserEvent('hide-form');

            $this->alert('success', __('site.updateSuccessfully'), [

                'position'  =>  'top-end',
                'timer'  =>  2000,
                'timerProgressBar' => true,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);

        } catch (\Throwable $th) {

            $message = $this->alert('error', $th->getMessage(), [

                'position'  =>  'top-end',
                'timer'  =>  3000,
                'timerProgressBar' => true,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);

            Log::error($th->getMessage());

            return $message;
        }
    }

    // Show Modal Form to Confirm Task Removal

    public function confirmTaskRemoval($taskId)
    {
        $this->taskIdBeingRemoved = $taskId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete Task

    public function deleteTask()
    {
        try {
            $task = Task::findOrFail($this->taskIdBeingRemoved);

            $task->delete();

            $task = null;

            $this->dispatchBrowserEvent('hide-delete-modal');

            $this->alert('success', __('site.deleteSuccessfully'), [
                'position'  =>  'top-end',
                'timer'  =>  2000,
                'timerProgressBar' => true,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);
        } catch (\Throwable $th) {

            $message = $this->alert('error', $th->getMessage(), [

                'position'  =>  'top-end',
                'timer'  =>  3000,
                'timerProgressBar' => true,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);
            return $message;
        }
    }

    public function getLevelsData()
    {
        $user_office_id = auth()->user()->office_id;

        $selected_office_id = $this->byOffice;

        $byOffice = $selected_office_id ? $selected_office_id : $user_office_id;

        $office_type = Office::where('id', $byOffice)->pluck('office_type')->first();

        $this->levels = Level::whereIn('id', $office_type == 1 ? [1, 2, 3, 4, 5, 6, 7] : [7])->get();

        // $this->levels = Level::whereIn('id', [1, 2, 3, 4, 5, 6, 7])
        //     ->whereHas('tasks', function ($query) use ($byOffice) {$query->where('office_id', $byOffice);})
        //     ->get();
    }

    public function OfficeOption($id)
    {
        $this->byOffice = $id;
        $this->getLevelsData();
    }

    public function getTasksProperty()
	{
        $searchString = $this->searchTerm;
        $byOffice = $this->byOffice ? $this->byOffice : auth()->user()->office_id;
        $byLevel = $this->byLevel;

        $tasks = Task::with('events')->where('office_id', $byOffice)
            ->when($byLevel, function ($query) use($byLevel) {
                $query->where('level_id', $byLevel);
            })
            //->where('level_id', $byLevel)
            ->search(trim(($searchString)))
            ->orderBy('level_id','ASC')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return $tasks;
	}

    public function render()
    {
        $tasks = $this->tasks;
        $levels = $this->getLevelsData();

        $offices = Office::whereStatus(true)
            ->where('gender', auth()->user()->gender)
            ->where('education_id', auth()->user()->office->education->id)
            ->get();

        return view('livewire.backend.tasks.tasks' ,compact(
            'tasks',
            'offices',
            'levels',
        ))->layout('layouts.admin');
    }
}
