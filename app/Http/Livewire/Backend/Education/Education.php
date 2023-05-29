<?php

namespace App\Http\Livewire\Backend\Education;

use PDF;
use App\Models\Education as ModelsEducation;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Education extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $education;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $showEditModal = false;

    public $educationIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteEducations'];

    // Updated Select Page Rows

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->educations->pluck('id')->map(function ($id) {
                return (string) $id;
            });
        } else {
            $this->reset(['selectedRows', 'selectPageRows']);
        }
    }

    public function resetSelectedRows()
    {
        $this->reset(['selectedRows', 'selectPageRows']);
    }

    // show Sweetalert Confirmation for Delete

    public function deleteSelectedRows()
    {
        $this->dispatchBrowserEvent('show-delete-alert-confirmation');
    }

    // Updated Search Term
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // set All selected Education As Active

    public function setAllAsActive()
	{
		ModelsEducation::whereIn('id', $this->selectedRows)->update(['status' => 1]);

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

    // set All selected Education As InActive

    public function setAllAsInActive()
    {
        ModelsEducation::whereIn('id', $this->selectedRows)->update(['status' => 0]);

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

    // Delete Selected Education

    public function deleteEducations()
    {
        // delete selected Education from database
        ModelsEducation::whereIn('id', $this->selectedRows)->delete();

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

    // show add new Education form modal

    public function addNewEducation()
    {
        $this->reset('data');
        $this->showEditModal = false;
        $this->data['status'] = 1;
        $this->dispatchBrowserEvent('show-form');
    }

    public function createEducation()
    {
        $validatedData = Validator::make($this->data, [

			'name'     => 'required',
			'status'   => 'required',

		])->validate();

		ModelsEducation::create($validatedData);

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

    // show Update new Education form modal

    public function edit(ModelsEducation $education)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->education = $education;

        $this->data = $education->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    // Update Task

    public function updateEducation()
    {

        $validatedData = Validator::make($this->data, [

            'name'                       => 'required',
            'status'                     => 'required',

        ])->validate();

        $this->education->update($validatedData);

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
    }

    // Show Modal Form to Confirm Office Removal

    public function confirmEducationRemoval($educationId)
    {
        $this->educationIdBeingRemoved = $educationId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete Office

    public function deleteEducation()
    {
        try {

            $education = ModelsEducation::findOrFail($this->educationIdBeingRemoved);

            $education->delete();

            $education = null;

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
                'timer'  =>  2000,
                'timerProgressBar' => true,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);
            return $message;

        }
    }

    // Export Education PDF File
    public function exportPDF()
    {
        try {

            $educations = ModelsEducation::orderBy('name', 'asc')->get();

            if ($educations->count() <> 0) {

                return response()->streamDownload(function() use($educations){

                    $pdf = PDF::loadView('livewire.backend.education.education_pdf',[
                        'educations' => $educations ,
                    ],[],[
                        'format' => 'A4-P',
                        'orientation' => 'P'
                    ]);

                    return $pdf->stream('educations');

                },'education.pdf');

            } else {

                $this->alert('error', __('site.noDataFound'), [
                    'position'  =>  'center',
                    'timer'  =>  2000,
                    'timerProgressBar' => true,
                    'toast'  =>  true,
                    'text'  =>  null,
                    'showCancelButton'  =>  false,
                    'showConfirmButton'  =>  false
                ]);
            }

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

    public function getEducationsProperty()
	{
        $searchString = $this->searchTerm;

        $educations = ModelsEducation::search(trim(($searchString)))
                    ->orderBy('name')
                    ->paginate(50);

        return $educations;
	}

    public function render()
    {
        $educations = $this->educations;

        return view('livewire.backend.education.education', compact(

            'educations',

        ))->layout('layouts.admin');
    }
}
