<?php

namespace App\Http\Livewire\Backend\Users;

use PDF;
use App\Models\Role;
use App\Models\User;
use App\Models\Office;
use App\Models\JobType;
use Livewire\Component;
use App\Models\Education;
use App\Models\SectionType;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Users extends Component
{
    use WithPagination;
    use WithFileUploads;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $data = [];
    public $user;

    public $searchTerm = null;
    protected $queryString = ['searchTerm' => ['except' => '']];

    public $byOffice = null; //filter by office_id

    public $showEditModal = false;

    public $userIdBeingRemoved = null;

    public $selectedRows = [];
	public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteUsers'];

    public function updateUserRole(User $user ,$role)
    {
        Validator::make(['role' => $role],[
            'role' => 'required|in:2,3',
            // 'role' => 'required',
        ])->validate();

        $user->roles()->sync($role);

        $this->alert('success', __('site.updateRoleSuccessfully'), [
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
            $this->selectedRows = $this->users->pluck('id')->map(function ($id) {
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

    // set All selected User As Active

    public function setAllAsActive()
    {
        User::whereIn('id', $this->selectedRows)->update(['status' => 1]);

        $this->alert('success', __('site.activeSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  3000,
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
		User::whereIn('id', $this->selectedRows)->update(['status' => 0]);

        $this->alert('success', __('site.inActiveSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  3000,
            'timerProgressBar' => true,
            'toast'  =>  true,
            'text'  =>  null,
            'showCancelButton'  =>  false,
            'showConfirmButton'  =>  false
        ]);

		$this->reset(['selectPageRows', 'selectedRows']);
	}

    // Delete Selected User with relationship roles And permission

    public function deleteUsers()
    {
        // delete roles and permissions for selected users from database
        DB::table('role_user')->whereIn('user_id', $this->selectedRows)->delete();
        DB::table('permission_user')->whereIn('user_id', $this->selectedRows)->delete();

        // delete selected users from database
		User::whereIn('id', $this->selectedRows)->delete();

        $this->alert('success', __('site.deleteSuccessfully'), [
            'position'  =>  'top-end',
            'timer'  =>  3000,
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

    // show add new user form modal

    public function addNewUser()
    {
        $this->reset('data');
        $this->showEditModal = false;
        $this->data['role_id'] = 3;
        $this->data['status'] = 1;
        $this->dispatchBrowserEvent('show-form');
    }

    // show Update new user form modal

    public function edit(User $user)
    {
        $this->reset('data');

		$this->showEditModal = true;

		$this->user = $user;

		$this->data = $user->toArray();

		$this->dispatchBrowserEvent('show-form');
    }

    // Show user details

    public function show(User $user)
    {
        $this->reset('data');

		$this->user = $user;

		$this->data = $user->toArray();

        $this->data['role_id'] = $user->roles[0]->id;

        $this->data['office'] = $user->office->name;

        $this->data['specialization'] = $user->specialization->name;

        $this->data['type'] = $user->type;

        $this->data['edu_type'] = $user->edu_type;

        $this->data['created_at'] = $user->created_at;

		$this->dispatchBrowserEvent('show-modal-show');
    }

    // Delete User

    public function deleteUser()
    {
        try {
            $user = User::findOrFail($this->userIdBeingRemoved);

            // delete roles and permissions for selected users from database
            DB::table('role_user')->where('user_id', $this->userIdBeingRemoved)->delete();
            DB::table('permission_user')->where('user_id', $this->userIdBeingRemoved)->delete();

            $user->delete();

            $this->dispatchBrowserEvent('hide-delete-modal');

            $this->alert('success', __('site.deleteSuccessfully'), [
                'position'  =>  'top-end',
                'timer'  =>  3000,
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

    public function getUsersProperty()
	{

        $searchString = $this->searchTerm;
        $byOffice = $this->byOffice ? $this->byOffice : auth()->user()->office_id;

        $users = User::where('office_id', $byOffice)
                        ->where(function ($qu) {
                            $qu->whereHas('roles', function ($q) {
                                $q->whereNot('name', 'superadmin');
                        });
                    })
                    ->search(trim(($searchString)))
                    ->paginate(50);

        return $users;
	}

    public function render()
    {
        $users = $this->users;

        $specializations = Specialization::whereStatus(true)->orderBy('name', 'asc')->get();

        $offices = Office::whereStatus(true)->where('education_id' , auth()->user()->office->education_id)->get();

        $roles = Role::whereNotIn('id',[1])->get();

        $jobs_type = JobType::whereStatus(true)->get();

        $educations         = Education::where('status', true)->get();

        $section_types      = SectionType::whereStatus(true)->get();

        $genders = [
            [
                'id'    => 1,
                'name' => __('site.male')
            ],
            [
                'id'    => 0,
                'name' => __('site.female')
            ]
        ];

        return view('livewire.backend.users.users', compact(
            'users' ,
            'specializations' ,
            'offices' ,
            'roles' ,
            'jobs_type' ,
            'educations' ,
            'section_types' ,
            'genders' ,
        ))->layout('layouts.admin');
    }
}