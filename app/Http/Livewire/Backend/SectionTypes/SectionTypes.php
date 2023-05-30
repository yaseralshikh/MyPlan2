<?php

namespace App\Http\Livewire\Backend\SectionTypes;

use Livewire\Component;
use App\Models\SectionType;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SectionTypes extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $sectiontype;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $showEditModal = false;

    public $sectiontypeIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteSectionTypes'];


    // Updated Select Page Rows

    public function updatedSelectPageRows($value)
    {
        if ($value) {

            $this->selectedRows = $this->sectiontypes->pluck('id')->map(function ($id) {
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

    // set All selected SectionType As Active

    public function setAllAsActive()
	{
		SectionType::whereIn('id', $this->selectedRows)->update(['status' => 1]);

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

    // set All selected SectionType As InActive

	public function setAllAsInActive()
	{
		SectionType::whereIn('id', $this->selectedRows)->update(['status' => 0]);

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

    // Delete Selected SectionTypes

    public function deleteSectionTypes()
    {
        // delete selected Levels from database
        SectionType::whereIn('id', $this->selectedRows)->delete();

        $this->alert('success', __('site.deleteSuccessfully'), [

            'position'  =>  'top-end',
            'timer'  =>  2000,
            'timerProgressBar' => true,
            'toast'  =>  true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);

        $this->reset('data');
    }

    // Updated Search Term
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    // show add new Level form modal

    public function addNewSectionType()
    {
        $this->reset('data');
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-form');
    }

    // Create new SectionType

    public function createSectionType()
    {
        $validatedData = Validator::make($this->data, [
			'name'                   => 'required|max:255',
		])->validate();


		SectionType::create($validatedData);

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

    // show Update new SectionType form modal

    public function edit(SectionType $sectiontype)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->sectiontype = $sectiontype;

        $this->data = $sectiontype->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    // Update SectionType

    public function updateSectionType()
    {
        try {

            $validatedData = Validator::make($this->data, [

                'name'                   => 'required|max:255',

            ])->validate();

            $this->sectiontype->update($validatedData);

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

    // Show Modal Form to Confirm SectionType Removal

    public function confirmSectionTypeRemoval($SectionTypeId)
    {
        $this->sectiontypeIdBeingRemoved = $SectionTypeId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete SectionType

    public function deleteSectionType()
    {
        try {
            $section_type = SectionType::findOrFail($this->sectiontypeIdBeingRemoved);

            $section_type->delete();

            $section_type = null;

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

    public function getSectionTypesProperty()
	{
        $SectionTypes = SectionType::query()
            ->where('name', 'like', '%'.$this->searchTerm.'%')
            ->orderBy('name' , 'asc')
            ->paginate(30);

        return $SectionTypes;
	}

    public function render()
    {
        $section_types = $this->SectionTypes;

        return view('livewire.backend.section-types.section-types', compact((
            'section_types'
        )))->layout('layouts.admin');
    }
}
