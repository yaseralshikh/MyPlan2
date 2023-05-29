<?php

namespace App\Http\Livewire\Backend\Semesters;

use Livewire\Component;
use App\Models\Semester;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Semesters extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $semester;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $byStatus = 1;

    public $showEditModal = false;

    public $semesterIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteSemesters'];

    public function changeActive($semesterId)
    {
        $semester =Semester::where('id' ,$semesterId)->get();
        if ($semester[0]->active) {
            Semester::where('id' ,$semesterId)->update(['active' => 0]);
        } else {
            Semester::where('id' ,$semesterId)->update(['active' => 1]);
            Semester::whereNotIn('id' ,[$semesterId])->update(['active' => 0]);
        }

        $this->alert('success', __('site.semesterActiveSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  2000,
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
            $this->selectedRows = $this->semesters->pluck('id')->map(function ($id) {
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
		Semester::whereIn('id', $this->selectedRows)->update(['status' => 1]);

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
		Semester::whereIn('id', $this->selectedRows)->update(['status' => 0]);

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

    // Delete Selected Semesters

    public function deleteSemesters()
    {
        // delete selected Semesters from database
        Semester::whereIn('id', $this->selectedRows)->delete();

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

    // show add new Semester form modal

    public function addNewSemester()
    {
        $this->reset('data');
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-form');
    }

    // Create new Semester

    public function createSemester()
    {
        $validatedData = Validator::make($this->data, [

			'name'              => 'required',
			'start'             => 'required|date',
			'end'               => 'required|date',
			'school_year'       => 'required|numeric',

		])->validate();

		Semester::create($validatedData);

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

    // show Update new Semester form modal

    public function edit(Semester $semester)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->semester = $semester;

        $this->data = $semester->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    // Update Task

    public function updateSemester()
    {
        try {
            $validatedData = Validator::make($this->data, [
                'name'           => 'required',
                'start'          => 'required|date',
                'end'            => 'required|date',
                'school_year'    => 'required|numeric',
            ])->validate();

            $this->semester->update($validatedData);

            // Semester::whereNotIn('id' ,[$semesterId])->update(['active' => 0]);

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

    // Show Modal Form to Confirm Semester Removal

    public function confirmSemesterRemoval($semesterId)
    {
        $this->semesterIdBeingRemoved = $semesterId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete Semester

    public function deleteSemester()
    {
        try {
            $semester = Semester::findOrFail($this->semesterIdBeingRemoved);

            $semester->delete();

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

    public function getSemestersProperty()
	{
        $searchString = $this->searchTerm;
        $byStatus = $this->byStatus;

        $semesters = Semester::where('status', $byStatus)
            ->search(trim(($searchString)))
            ->orderBy('start', 'asc')
            ->orderBy('id','asc')
            ->paginate(30);

        return $semesters;
	}

    public function render()
    {
        $semesters = $this->semesters;

        return view('livewire.backend.semesters.semesters', compact(

            'semesters',

        ))->layout('layouts.admin');
    }
}
