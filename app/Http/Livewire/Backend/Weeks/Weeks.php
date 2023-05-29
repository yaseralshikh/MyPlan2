<?php

namespace App\Http\Livewire\Backend\Weeks;

use App\Models\Week;
use Livewire\Component;
use App\Models\Semester;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Weeks extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $week;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $byStatus = 1; // filter bt status
    public $bySemester = null; // filter bt Semester

    public $showEditModal = false;

    public $weekIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteWeeks'];

    // Updated Select Page Rows

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->weeks->pluck('id')->map(function ($id) {
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
		Week::whereIn('id', $this->selectedRows)->update(['status' => 1]);

        $this->alert('success', __('site.activeSuccessfully'), [

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

    // set All selected User As InActive

	public function setAllAsInActive()
	{
		Week::whereIn('id', $this->selectedRows)->update(['status' => 0]);

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

    public function deleteWeeks()
    {
        // delete selected users from database
		Week::whereIn('id', $this->selectedRows)->delete();

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

    public function addNewWeek()
    {
        $this->reset('data');
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-form');
    }

    // Create new user

    public function createWeek()
    {
        $validatedData = Validator::make($this->data, [

            'name'                     => 'required',
            'start'                    => 'required|date',
            'end'                      => 'required|date',
            'semester_id'              => 'required',

		])->validate();


		Week::create($validatedData);

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

    public function edit(Week $week)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->week = $week;

        $this->data = $week->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    // Update Week

    public function updateWeek()
    {
        try {
            $validatedData = Validator::make($this->data, [

                'name'                     => 'required',
                'start'                    => 'required|date',
                'end'                      => 'required|date',
                'semester_id'              => 'required',

            ])->validate();

            $this->week->update($validatedData);

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

    // Show Modal Form to Confirm Week Removal

    public function confirmWeekRemoval($weekId)
    {
        $this->weekIdBeingRemoved = $weekId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete Week

    public function deleteWeek()
    {
        try {
            $week = Week::findOrFail($this->weekIdBeingRemoved);

            $week->delete();

            $week = null;

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

            Log::error($th->getMessage());
            return $message;
        }
    }

    // Get Semester Active
    public function semesterActive()
    {
        $semester_active = Semester::where('active' ,1)->get();
        return $semester_active[0]->id;
    }

    public function getWeeksProperty()
	{
        $searchString = $this->searchTerm;
        $byStatus = $this->byStatus;
        $bySemester = $this->bySemester ? $this->bySemester : $this->semesterActive();

        $weeks = Week::where('semester_id', $bySemester)
            ->where('status', $byStatus)
            ->search(trim(($searchString)))
            ->orderBy('start', 'asc')
            ->paginate(30);

        return $weeks;
	}


    public function render()
    {
        $weeks = $this->weeks;
        $semesters = Semester::where('status',1)->get();

        return view('livewire.backend.weeks.weeks', compact(

            'weeks',
            'semesters'

        ))->layout('layouts.admin');
    }
}
