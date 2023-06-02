<?php

namespace App\Http\Livewire\Backend\SubTasks;

use App\Models\Office;
use App\Models\Subtask;
use Livewire\Component;
use App\Models\SectionType;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SubTasks extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $subtask;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $byOffice = null; //filter by office_id
    public $bySectionType = 1; // filter bt edu_type

    public $showEditModal = false;

    public $subtaskIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteSubtasks'];


    // Updated Select Page Rows

    public function updatedSelectPageRows($value)
    {
        if ($value) {
            $this->selectedRows = $this->subtasks->pluck('id')->map(function ($id) {
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

    // set All selected Subtasks As Active

    public function setAllAsActive()
	{
		Subtask::whereIn('id', $this->selectedRows)->update(['status' => 1]);

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

    // set All selected Subtasks As InActive

	public function setAllAsInActive()
	{
		Subtask::whereIn('id', $this->selectedRows)->update(['status' => 0]);

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

    // Delete Selected Subtasks

    public function deleteSubtasks()
    {
        // delete selected Subtasks from database
        Subtask::whereIn('id', $this->selectedRows)->delete();

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

    // show add new Subtask form modal

    public function addNewSubtask()
    {
        $this->reset('data');
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-form');
    }

    // Create new Subtask

    public function createSubtask()
    {
        $validatedData = Validator::make($this->data, [

			'name'                   => 'required',
			'section'                => 'required',
			'section_type_id'        => 'required',
            'office_id'              => 'nullable',

		])->validate();

        $validatedData['position'] = 0;

        if(empty($validatedData['office_id'])) {
            $validatedData['office_id'] = auth()->user()->office_id;
        }

		Subtask::create($validatedData);

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

    // show Update Subtask form modal

    public function edit(Subtask $subtask)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->subtask = $subtask;

        $this->data = $subtask->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    // Update Subtask

    public function updateSubtask()
    {
        $validatedData = Validator::make($this->data, [

            'name'            => 'required',
            'section'         => 'required',
            'section_type_id' => 'required',
            'office_id'       => 'required',

        ])->validate();

        $this->subtask->update($validatedData);

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

    // Show Modal Form to Confirm Subtask Removal

    public function confirmSubtaskRemoval($subtaskId)
    {
        $this->subtaskIdBeingRemoved = $subtaskId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete Subtask

    public function deleteSubtask()
    {
        try {
            $subtask = Subtask::findOrFail($this->subtaskIdBeingRemoved);

            $subtask->delete();

            $subtask= null;

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

    public function updateSubtaskPosition($items)
    {
        foreach ($items as $item) {

            Subtask::find($item['value'])->update(['position' => $item['order']]);

        }

        $this->alert('success', __('site.updateSubtaskPositionSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  2000,
            'timerProgressBar' => true,
            'toast'  =>  true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);
    }

    // get Subtasks Property

    public function getSubtasksProperty()
	{
        $searchString = $this->searchTerm;
        $byOffice = $this->byOffice ? $this->byOffice : auth()->user()->office_id;
        $bySectionType = $this->bySectionType;

        $subtasks = Subtask::where('office_id', $byOffice)
            ->when($bySectionType, function ($query) use($bySectionType) {
                    $query->where('section_type_id', $bySectionType);
            })
            ->search(trim(($searchString)))
            ->orderBy('position', 'asc')
            ->orderBy('section', 'asc')
            ->paginate(30);

        return $subtasks;
	}

    public function render()
    {
        $subtasks = $this->subtasks;
        $sectionTypes = SectionType::whereStatus(true)->get();
        $user_education_id = auth()->user()->office->education_id;
        $user_gender = auth()->user()->office->gender;

        $offices = Office::where('education_id', $user_education_id)
            ->where('gender' , $user_gender)
            ->orderBy('name')
            ->get();

        $sections = [
            [
                'id'    => 1,
                'name' => 'مهمة فرعية'
            ],
            [
                'id'    => 2,
                'name' => 'حاشية'
            ],
        ];

        return view('livewire.backend.sub-tasks.sub-tasks', compact(

            'subtasks',
            'offices',
            'sections',
            'sectionTypes',

        ))->layout('layouts.admin');
    }
}
