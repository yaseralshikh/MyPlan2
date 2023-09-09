<?php

namespace App\Http\Livewire\Backend\Users;

use App\Exports\UsersExport;
use App\Models\Education;
use App\Models\JobType;
use App\Models\Office;
use App\Models\Role;
use App\Models\SectionType;
use App\Models\Specialization;
use App\Models\User;
use App\Rules\ArabicText;
use App\Rules\MobileNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Carbon;

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
    public $byGender = 1;

    public $showEditModal = false;

    public $userIdBeingRemoved = null;

    public $selectedRows = [];
    public $selectPageRows = false;
    protected $listeners = ['deleteConfirmed' => 'deleteUsers'];

    public function updateUserRole(User $user, $role)
    {
        Validator::make(['role' => $role], [
            'role' => 'required|in:2,3',
            // 'role' => 'required',
        ])->validate();

        $user->roles()->sync($role);

        $this->alert('success', __('site.updateRoleSuccessfully'), [
            'position' => 'top-end',
            'timer' => 1500,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
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
            'position' => 'top-end',
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // set All selected User As InActive

    public function setAllAsInActive()
    {
        User::whereIn('id', $this->selectedRows)->update(['status' => 0]);

        $this->alert('success', __('site.inActiveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // set All selected User As email verified

    public function setAllAs_EmailVerified()
    {
        User::whereIn('id', $this->selectedRows)->update(['email_verified_at' => Carbon::now()]);

        $this->alert('success', __('site.inActiveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);

        $this->reset(['selectPageRows', 'selectedRows']);
    }

    // set All selected User As Not email verified

    public function setAllAsNot_EmailVerified()
    {
        User::whereIn('id', $this->selectedRows)->update(['email_verified_at' => Null]);

        $this->alert('success', __('site.inActiveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 3000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
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
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
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

    // Create new user

    public function createUser()
    {
        $regex = auth()->user()->roles[0]->name == 'operationsmanager' ? '' : 'regex:/@moe\.gov.sa$/i';

        $validatedData = Validator::make($this->data, [
            'name' => ['required', 'string', 'max:255', new ArabicText, 'unique:users'],
            'email' => ['required', 'string', 'email', $regex, 'max:50', 'unique:users'],
            'mobile' => ['nullable', new MobileNumber],
            'office_id' => 'nullable',
            'specialization_id' => 'required',
            'job_type_id' => 'required',
            'section_type_id' => 'required',
            'gender' => 'required',
            'email_verified_at' => 'nullable|date',
            'password' => 'required|min:8|confirmed',
            'status' => 'required',
        ])->validate();

        $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['education_id'] = auth()->user()->education_id;
        $validatedData['email_verified_at'] = Carbon::now();

        if (empty($validatedData['office_id'])) {
            $validatedData['office_id'] = auth()->user()->office_id;
        }

        $user = User::create($validatedData);
        $user->addRole(4);

        $this->dispatchBrowserEvent('hide-form');

        $this->alert('success', __('site.saveSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);
    }

    // show Update new user form modal

    public function edit(User $user)
    {
        $this->reset('data');

        $this->showEditModal = true;

        $this->user = $user;

        $this->data = $user->toArray();

        $this->data['email_verified_at'] = $this->user->email_verified_at ? $this->user->email_verified_at->format('Y-m-d\TH:i:s') : '';

        $this->dispatchBrowserEvent('show-form');
    }

    // Update User

    public function updateUser()
    {
        $regex = auth()->user()->roles[0]->name == 'operationsmanager' ? '' : 'regex:/@moe\.gov.sa$/i';

        $validatedData = Validator::make($this->data, [
            'name' => ['required', 'string', 'max:255', new ArabicText, 'unique:users,name,' . $this->user->id],
            'email' => ['required', 'string', 'email', $regex, 'max:50', 'unique:users,email,' . $this->user->id],
            'mobile' => ['nullable', new MobileNumber],
            'office_id' => 'nullable',
            'specialization_id' => 'required',
            'job_type_id' => 'required',
            'section_type_id' => 'required',
            'gender' => 'required',
            'status' => 'required',
            'password' => 'sometimes|min:8|confirmed',
            'email_verified_at' => 'nullable|date',
        ])->validate();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        if ($validatedData['email'] != $this->user->getOriginal('email')) {
            $validatedData['email_verified_at'] = null;
        }

        if ($validatedData['email_verified_at'] == null) {
            // Remove 'email_verified_at' key if its value is null
            //unset($validatedData['email_verified_at']);
            $validatedData['email_verified_at'] = null;
        }

        $this->user->update($validatedData);

        $this->dispatchBrowserEvent('hide-form');

        $this->alert('success', __('site.updateSuccessfully'), [
            'position' => 'top-end',
            'timer' => 2000,
            'timerProgressBar' => true,
            'toast' => true,
            'text' => null,
            'showCancelButton' => false,
            'showConfirmButton' => false,
        ]);
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

        $this->data['job_type'] = $user->job_type->name;

        $this->data['section_type'] = $user->section_type->name;

        $this->data['created_at'] = $user->created_at;

        $this->dispatchBrowserEvent('show-modal-show');
    }

    // Show Modal Form to Confirm User Removal

    public function confirmUserRemoval($userId)
    {
        $this->userIdBeingRemoved = $userId;

        $this->dispatchBrowserEvent('show-delete-modal');
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

            $user = null;

            $this->dispatchBrowserEvent('hide-delete-modal');

            $this->alert('success', __('site.deleteSuccessfully'), [
                'position' => 'top-end',
                'timer' => 3000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
        } catch (\Throwable $th) {
            $message = $this->alert('error', $th->getMessage(), [
                'position' => 'top-end',
                'timer' => 3000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);
            return $message;
        }
    }

    // Export Excel File
    public function exportExcel()
    {
        return Excel::download(new UsersExport($this->searchTerm, $this->selectedRows, $this->byOffice ? $this->byOffice : auth()->user()->office_id), 'users.xlsx');
    }

    // Export User PDF File
    public function exportPDF()
    {
        try {
            if ($this->selectedRows) {

                $users = User::whereIn('id', $this->selectedRows)->orderBy('name', 'asc')->get();

            } else {

                $users = User::where('office_id', $this->byOffice ? $this->byOffice : auth()->user()->office_id)
                    ->orderBy('name', 'asc')
                    ->get();
            }

            if ($users->count() != 0) {

                return response()->streamDownload(function () use ($users) {

                    $pdf = PDF::loadView('livewire.backend.users.users_pdf', [
                        'users' => $users,
                    ], [], [
                        'format' => 'A4-L',
                        'orientation' => 'L',
                    ]);

                    return $pdf->stream('users');

                }, 'users.pdf');

            } else {

                $this->alert('error', __('site.noDataFound'), [
                    'position' => 'center',
                    'timer' => 3000,
                    'timerProgressBar' => true,
                    'toast' => true,
                    'text' => null,
                    'showCancelButton' => false,
                    'showConfirmButton' => false,
                ]);
            }

        } catch (\Throwable $th) {

            $message = $this->alert('error', $th->getMessage(), [
                'position' => 'top-end',
                'timer' => 3000,
                'timerProgressBar' => true,
                'toast' => true,
                'text' => null,
                'showCancelButton' => false,
                'showConfirmButton' => false,
            ]);

            return $message;
        }
    }

    public function getUsersProperty()
    {

        $searchString = $this->searchTerm;

        $byGender = auth()->user()->roles[0]->name == 'admin' ? auth()->user()->gender : $this->byGender;

        $byOffice = $this->byOffice ? $this->byOffice : auth()->user()->office_id;

        $users = User::where('office_id', $byOffice)
            ->where(function ($qu) {
                $qu->whereHas('roles', function ($q) {
                    $q->whereNotIn('name', ['superadmin', 'operationsmanager']);
                });
            })
            ->search(trim(($searchString)))
            ->where('gender', $byGender)
            ->latest()
            ->paginate(50);

        return $users;
    }

    public function render()
    {
        $users = $this->users;
        $byGender = auth()->user()->roles[0]->name == 'admin' ? auth()->user()->gender : $this->byGender;

        $specializations = Specialization::whereStatus(true)->orderBy('name', 'asc')->get();

        $offices = Office::whereStatus(true)->where('gender', $byGender)->where('education_id', auth()->user()->office->education_id)->get();

        $roles = Role::whereNotIn('id', [1, 2])->get();

        $jobs_type = JobType::whereStatus(true)->orderBy('name', 'asc')->get();

        $educations = Education::where('status', true)->orderBy('name', 'asc')->get();

        $section_types = SectionType::whereStatus(true)->orderBy('name', 'asc')->get();

        $genders = [
            [
                'id' => 1,
                'name' => __('site.male'),
            ],
            [
                'id' => 0,
                'name' => __('site.female'),
            ],
        ];

        return view('livewire.backend.users.users', compact(
            'users',
            'specializations',
            'offices',
            'roles',
            'jobs_type',
            'educations',
            'section_types',
            'genders',
        ))->layout('layouts.admin');
    }
}
