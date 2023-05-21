<?php

namespace App\Http\Livewire\Backend\Education;

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

    public function getEducationsProperty()
	{
        $searchString = $this->searchTerm;

        $educations = ModelsEducation::search(trim(($searchString)))
                    ->latest()
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
