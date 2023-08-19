<?php

namespace App\Http\Livewire\Backend\Offices;

use PDF;
use Throwable;
use App\Models\Task;
use App\Models\Office;
use Livewire\Component;
use App\Models\Education;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Offices extends Component
{
    use WithFileUploads;
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $office;

    public $director_signature_image;
    public $assistant_signature_image;
    public $assistant2_signature_image;
    public $assistant3_signature_image;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $byStatus = 1;
    public $byOfficeType = 1;
    public $byGender = 1;
    public $byEducation = null;

    public $showEditModal = false;

    public $officeIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteOffices'];

    // update Allowed Create Plan Status

    public function updateAllowedCreatePlanStatus(Office $office, $value)
    {
        $office->allowed_create_plans = $value;
        $office->save();

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

    // update Allowed OverLap Value

    public function updateAllowedOverLapValue(Office $office, $value)
    {
        $office->allowed_overlap = $value;
        $office->save();

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

            $this->selectedRows = $this->offices->pluck('id')->map(function ($id) {
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

    // set All selected office As Active

    public function setAllAsActive()
	{
		Office::whereIn('id', $this->selectedRows)->update(['status' => 1]);

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

    // set All selected Office As InActive

    public function setAllAsInActive()
    {
        Office::whereIn('id', $this->selectedRows)->update(['status' => 0]);

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

    // Delete all Selected Offices

    public function deleteOffices()
    {
        // delete images for offices if exists from Storage folder
        $signatureImages = Office::whereIn('id', $this->selectedRows)->get(['director_signature_path']);
        foreach($signatureImages as $signatureImage){
            $imageFileName = $signatureImage->director_signature_path;
            if($imageFileName){
                Storage::disk('signature_photos')->delete($imageFileName);
            }
        }

        $signatureImages = Office::whereIn('id', $this->selectedRows)->get(['assistant_signature_path']);
        foreach($signatureImages as $signatureImage){
            $imageFileName = $signatureImage->assistant_signature_path;
            if($imageFileName){
                Storage::disk('signature_photos')->delete($imageFileName);
            }
        }

        $signatureImages = Office::whereIn('id', $this->selectedRows)->get(['assistant2_signature_path']);
        foreach($signatureImages as $signatureImage){
            $imageFileName = $signatureImage->assistant2_signature_path;
            if($imageFileName){
                Storage::disk('signature_photos')->delete($imageFileName);
            }
        }

        $signatureImages = Office::whereIn('id', $this->selectedRows)->get(['assistant3_signature_path']);
        foreach($signatureImages as $signatureImage){
            $imageFileName = $signatureImage->assistant3_signature_path;
            if($imageFileName){
                Storage::disk('signature_photos')->delete($imageFileName);
            }
        }

        // delete selected Offices from database
        Office::whereIn('id', $this->selectedRows)->delete();

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

    // show add new Office form modal

    public function addNewOffice()
    {
        $this->reset('data');
        $this->data['gender'] = 1;
        $this->data['status'] = 1;
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-form');
    }

    public function createOffice()
    {
        $validatedData = Validator::make($this->data, [

			'education_id'               => 'required',
			'name'                       => 'required',
			'director'                   => 'required',
            'director_signature_path'    => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'assistant_signature_path'   => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'assistant2_signature_path'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'assistant3_signature_path'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'office_type'                => 'required',
            'gender'                     => 'required',
            'status'                     => 'required',

		])->validate();


        if ($this->director_signature_image) {
            $validatedData['director_signature_path'] = $this->director_signature_image->store('/', 'signature_photos');
		}

        if ($this->assistant_signature_image) {
            $validatedData['assistant_signature_path'] = $this->assistant_signature_image->store('/', 'signature_photos');
		}

        if ($this->assistant2_signature_image) {
            $validatedData['assistant2_signature_path'] = $this->assistant2_signature_image->store('/', 'signature_photos');
		}

        if ($this->assistant3_signature_image) {
            $validatedData['assistant3_signature_path'] = $this->assistant3_signature_image->store('/', 'signature_photos');
		}

		$$new_office = Office::create($validatedData);

        $tasks = [
            [
                'name' => 'يوم مكتبي',
            ],
            [
                'name' => 'إجازة',
            ],
            [
                'name' => 'برنامج تدريبي',
            ],
            [
                'name' => 'مكلف بمهمة',
            ],
        ];


        foreach($tasks as $task){
            Task::create([
                'name' => $task['name'],
                'office_id' => $$new_office->id,
                'level_id' => 7,
                'status' => 1,
            ]);
        };

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

    // show Update new office form modal

    public function edit(Office $office)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->office = $office;

        $this->data = $office->toArray();

        $this->dispatchBrowserEvent('show-form');
    }

    // Update Task

    public function updateOffice()
    {
        try {
            $validatedData = Validator::make($this->data, [

                'education_id'               => 'required',
                'name'                       => 'required',
                'director'                   => 'required',
                'director_signature_path'    => 'max:2048',
                'assistant_signature_path'   => 'max:2048',
                'assistant2_signature_path'  => 'max:2048',
                'assistant3_signature_path'  => 'max:2048',
                'office_type'                => 'required',
                'gender'                     => 'required',
                'status'                     => 'required',

            ])->validate();

            if ($this->director_signature_image) {
                if($this->office->director_signature_path){
                    Storage::disk('signature_photos')->delete($this->office->director_signature_path);
                }
                $validatedData['director_signature_path'] = $this->director_signature_image->store('/', 'signature_photos');
            }

            if ($this->assistant_signature_image) {
                if($this->office->assistant_signature_path){
                    Storage::disk('signature_photos')->delete($this->office->assistant_signature_path);
                }
                $validatedData['assistant_signature_path'] = $this->assistant_signature_image->store('/', 'signature_photos');
            }

            if ($this->assistant2_signature_image) {
                if($this->office->assistant2_signature_path){
                    Storage::disk('signature_photos')->delete($this->office->assistant2_signature_path);
                }
                $validatedData['assistant2_signature_path'] = $this->assistant2_signature_image->store('/', 'signature_photos');
            }

            if ($this->assistant3_signature_image) {
                if($this->office->assistant3_signature_path){
                    Storage::disk('signature_photos')->delete($this->office->assistant3_signature_path);
                }
                $validatedData['assistant3_signature_path'] = $this->assistant3_signature_image->store('/', 'signature_photos');
            }

            $this->office->update($validatedData);

            $this->director_signature_image = null;
            $this->assistant_signature_image = null;
            $this->assistant2_signature_image = null;
            $this->assistant3_signature_image = null;

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

        } catch (Throwable $th) {
            $message = $this->alert('error', $th->getMessage(), [
                'position'  =>  'center',
                'timer'  =>  2000,
                'timerProgressBar' => true,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);

            Log::error($th->getMessage());

            return $message;
        };
    }

    public function removeDirectorImage($officeId)
    {
        try {
            $office = Office::findOrFail($officeId);
            $directorFileName = $office->director_signature_path;

            if($directorFileName){
                Storage::disk('signature_photos')->delete($directorFileName);

                $office->update([
                    'director_signature_path' => null,
                ]);

                $this->director_signature_image = null;

                $this->alert('success', __('site.deleteSuccessfully'), [
                    'position'  =>  'center',
                    'timer'  =>  2000,
                    'timerProgressBar' => true,
                    'toast'  =>  true,
                    'text'  =>  null,
                    'showCancelButton'  =>  false,
                    'showConfirmButton'  =>  false
                ]);
            }

        } catch (Throwable $th) {

            $message = $this->alert('error', $th->getMessage(), [
                'position'  =>  'center',
                'timer'  =>  3000,
                'toast'  =>  true,
                'text'  =>  null,
                'showCancelButton'  =>  false,
                'showConfirmButton'  =>  false
            ]);

            Log::error($th->getMessage());

            return $message;
        }
    }

    public function removeAssistantImage($officeId)
    {
        try {
            $office = Office::findOrFail($officeId);
            $assistantFileName = $office->assistant_signature_path;

            if($assistantFileName){
                Storage::disk('signature_photos')->delete($assistantFileName);

                $office->update([
                    'assistant_signature_path' => null,
                ]);

                $this->assistant_signature_image = null;

                $this->alert('success', __('site.deleteSuccessfully'), [
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
                'position'  =>  'center',
                'timer'  =>  2000,
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

    public function removeAssistant2Image($officeId)
    {
        try {
            $office = Office::findOrFail($officeId);
            $assistant2FileName = $office->assistant2_signature_path;

            if($assistant2FileName){
                Storage::disk('signature_photos')->delete($assistant2FileName);

                $office->update([
                    'assistant2_signature_path' => null,
                ]);

                $this->assistant2_signature_image = null;

                $this->alert('success', __('site.deleteSuccessfully'), [
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
                'position'  =>  'center',
                'timer'  =>  2000,
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

    public function removeAssistant3Image($officeId)
    {
        try {
            $office = Office::findOrFail($officeId);
            $assistant3FileName = $office->assistant3_signature_path;

            if($assistant3FileName){
                Storage::disk('signature_photos')->delete($assistant3FileName);

                $office->update([
                    'assistant3_signature_path' => null,
                ]);

                $this->assistant3_signature_image = null;

                $this->alert('success', __('site.deleteSuccessfully'), [
                    'position'  =>  'center',
                    'timer'  =>  2000,
                    'timerProgressBar' => true,
                    'timerProgressBar' => true,
                    'toast'  =>  true,
                    'text'  =>  null,
                    'showCancelButton'  =>  false,
                    'showConfirmButton'  =>  false
                ]);
            }

        } catch (\Throwable $th) {

            $message = $this->alert('error', $th->getMessage(), [
                'position'  =>  'center',
                'timer'  =>  2000,
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

    // Show Modal Form to Confirm Office Removal

    public function confirmOfficeRemoval($officeId)
    {
        $this->officeIdBeingRemoved = $officeId;

        $this->dispatchBrowserEvent('show-delete-modal');
    }

    // Delete Office

    public function deleteOffice()
    {
        try {
            $office = Office::findOrFail($this->officeIdBeingRemoved);

            $directorFileName = $office->director_signature_path;
            $assistantFileName = $office->assistant_signature_path;
            $assistant2FileName = $office->assistant2_signature_path;
            $assistant3FileName = $office->assistant3_signature_path;

            if($directorFileName){
                Storage::disk('signature_photos')->delete($directorFileName);
            }

            if($assistantFileName){
                Storage::disk('signature_photos')->delete($assistantFileName);
            }

            if($assistant2FileName){
                Storage::disk('signature_photos')->delete($assistant2FileName);
            }

            if($assistant3FileName){
                Storage::disk('signature_photos')->delete($assistant3FileName);
            }

            $office->delete();

            $office= null;

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

            Log::error($th->getMessage());

            return $message;
        }
    }

    // Export Education PDF File
    public function exportPDF()
    {
        try {

            $offices = $this->offices;

            if ($offices->count() <> 0) {

                return response()->streamDownload(function() use($offices){

                    $pdf = PDF::loadView('livewire.backend.offices.offices_pdf',[
                        'offices' => $offices ,
                    ],[],[
                        'format' => 'A4-P',
                        'orientation' => 'P'
                    ]);

                    return $pdf->stream('offices');

                },'offices.pdf');

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

            Log::error($th->getMessage());

            return $message;
        }
    }

    public function getOfficesProperty()
	{
        $searchString   = $this->searchTerm;
        $officeType     = $this->byOfficeType;
        $byGender       = $this->byGender;
        $byEducation       = $this->byEducation ? $this->byEducation : auth()->user()->office->education->id;

        $offices = Office::where('office_type', $officeType)
            ->where('education_id', $byEducation)
            ->search(trim(($searchString)))
            ->where('gender' , $byGender)
            ->orderBy('name')
            ->paginate(50);

        return $offices;
	}

    public function render()
    {
        $offices = $this->offices;

        $educations =Education::whereStatus(true)->get();

        return view('livewire.backend.offices.offices', compact(

            'offices',
            'educations',

        ))->layout('layouts.admin');
    }
}
