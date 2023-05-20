<div>
    @section('style')
    <style>
        .disabled-link {
            cursor: default;
            pointer-events: none;
            text-decoration: none;
            color: rgb(174, 172, 172);
        }
    </style>
    @endsection

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">@lang('site.users')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.users')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <button wire:click.prevent='addNewUser' class="ml-1 btn btn-sm btn-primary">
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'مستخدم'])</span>
                            </i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm">@lang('site.action')</button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" dir="rtl">
                                {{-- @role('superadmin')
                                <a class="dropdown-item" wire:click.prevent="importExcelForm"
                                    href="#">@lang('site.importExcel')</a>
                                @endrole --}}
                                <a class="dropdown-item" wire:click.prevent="exportExcel" href="#"
                                    aria-disabled="true">@lang('site.exportExcel')</a>
                                <a class="dropdown-item" wire:click.prevent="exportPDF"
                                    href="#">@lang('site.exportPDF')</a>
                                <div class="dropdown-divider"></div>
                                {{-- @if ($selectedRows) --}}
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.setActive')</a>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.setInActive')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item {{ $selectedRows && auth()->user()->hasPermission('users-delete') ? 'text-danger' : 'disabled-link' }}  delete-confirm"
                                    wire:click.prevent="deleteSelectedRows" href="#">@lang('site.deleteSelected')</a>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        {{-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group d-flex justify-content-between align-items-center">
                        {{-- search --}}
                        <div class="input-group" style="width: 200px;">
                            <input type="search" wire:model="searchTerm" class="form-control"
                                placeholder="@lang('site.searchFor')" value="">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        @role('superadmin|operationsmanager')
                        {{-- offices Filter --}}
                        <div>
                            <select dir="rtl" name="office_id" wire:model="byOffice"
                                class="form-control form-control-sm">
                                <option value="" selected>@lang('site.choise', ['name' => 'مكتب التعليم'])</option>
                                @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endrole

                        <div>
                            <label class="flex-wrap">@lang('site.totalRecord', ['name' => 'المستخدمين']) : &nbsp{{
                                $users->total() }}</label>
                        </div>

                    </div>

                    @if ($selectedRows)
                        <span class="mb-2 text-success">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            selected
                            <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                            Str::plural('user', count($selectedRows)) }}
                            <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                                data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    <div class="table-responsive">
                        <table id="example2" class="table text-center table-bordered table-hover dtr-inline sortable"
                            aria-describedby="example2_info">
                            <thead class="bg-light">
                                <tr>
                                    <th class="align-middle">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </th>
                                    <th class="align-middle">#</th>
                                    <th class="align-middle">
                                        @lang('site.name')
                                    </th>
                                    <th class="align-middle">@lang('site.specialization')</th>
                                    <th class="align-middle">
                                        @lang('site.jobType')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.sectionType')
                                    </th>
                                    <th class="align-middle no-sort">@lang('site.role')</th>
                                    <th class="align-middle">@lang('site.emailVerified')</th>
                                    <th class="align-middle">
                                        @lang('site.status')
                                    </th>
                                    <th class="align-middle no-sort" colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr>
                                    <td scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectedRows" value="{{ $user->id }}"
                                                class="custom-control-input" id="{{ $user->id }}">
                                            <label class="custom-control-label" for="{{ $user->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td tabindex="0">{{ $user->name }}</td>
                                    <td>{{ $user->specialization->name }}</td>
                                    <td>{{ $user->job_type->name }}</td>
                                    <td>{{ $user->section_type->name }}</td>
                                    <td class="align-middle">
                                        <select class="form-control form-control-sm" {{ $user->roles[0]->name == 'operationsmanager' ? 'disabled' : ''}}
                                            wire:change='updateUserRole({{ $user }}, $event.target.value)'>
                                            @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->roles[0]->name == $role->name ?
                                                'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <span
                                            class="font-weight-bold badge text-white {{ $user->email_verified_at != Null ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $user->emailVerified() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="font-weight-bold badge text-white {{ $user->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $user->status() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button wire:click.prevent="edit({{ $user }})"
                                                class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>

                                            <button wire:click.prevent="show({{ $user }})"
                                                class="btn btn-info btn-sm"><i class="fa fa-user"></i></button>

                                            @if (auth()->user()->hasPermission('users-delete'))
                                                <button wire:click.prevent="confirmUserRemoval({{ $user->id }})"
                                                    class="btn btn-danger btn-sm"><i
                                                        class="fa fa-trash bg-danger"></i></button>
                                            @else
                                                <button class="btn btn-danger btn-sm" disabled><i
                                                        class="fa fa-trash bg-danger"></i></button>
                                            @endif
                                        </div>
                                        {{-- <form action="" method="post" id="delete-user-{{ $user->id }}"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form> --}}
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">@lang('site.noDataFound')</td>
                                </tr>
                                @endforelse
                            </tbody>
                            {{-- <tfoot>
                                <tr>
                                    <td colspan="5">
                                        {!! $users->appends(request()->all())->links() !!}
                                    </td>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $users->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update User -->

    <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateUser' : 'createUser' }}">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'مستخدم'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'مستخدم'])</span>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">

                                <!-- Modal User Full Name -->

                                <div  class="form-group">
                                    <label for="name">@lang('site.fullName')</label>
                                    <input dir="rtl" type="text" wire:model.defer="data.name"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        aria-describedby="nameHelp" placeholder="@lang('site.enterFullName')">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal User Email -->

                                <div class="form-group">
                                    <label for="email">@lang('site.email')</label>
                                    <input type="email" wire:model.defer="data.email"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        aria-describedby="emailHelp" placeholder="@lang('site.enterEmail')">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal User Office -->
                                @role('superadmin|operationsmanager')
                                <div class="form-group">
                                    <label for="office_id">@lang('site.office')</label>
                                    <select id="office_id" class="form-control @error('office_id') is-invalid @enderror"
                                        wire:model.defer="data.office_id">
                                        <option hidden>@lang('site.choise', ['name' => 'مكتب التعليم'])</option>
                                        @foreach ($offices as $office)
                                        <option class="bg-light" value="{{ $office->id }}">{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('office_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                @endrole

                                <!-- Modal User Specialization -->

                                <div class="form-group">
                                    <label for="specialization_id">@lang('site.specialization')</label>
                                    <select id="specialization_id"
                                        class="form-control @error('specialization_id') is-invalid @enderror"
                                        wire:model.defer="data.specialization_id">
                                        <option hidden>@lang('site.choise', ['name' => 'التخصص'])</option>
                                        @foreach ($specializations as $specialization)
                                        <option class="bg-light" value="{{ $specialization->id }}">{{
                                            $specialization->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('specialization_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal job_Type_id -->

                                <div class="form-group">
                                    <label for="job_type_id">@lang('site.type')</label>
                                    <select id="job_type_id" class="form-control @error('job_type_id') is-invalid @enderror"
                                        wire:model.defer="data.job_type_id">
                                        <option hidden selected>@lang('site.choise', ['name' => 'العمل الحالي'])</option>
                                        @foreach ($jobs_type as $job_type)
                                        <option class="bg-light" value="{{ $job_type->id }}">{{ $job_type->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('job_type_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Education Type -->

                                <div class="form-group">
                                    <label for="section_type_id">@lang('site.sectionType')</label>
                                    <select id="section_type_id" class="form-control @error('section_type_id') is-invalid @enderror"
                                        wire:model.defer="data.section_type_id">
                                        <option hidden selected>@lang('site.choise', ['name' => 'المرجع الإداري'])</option>
                                        @foreach ($section_types as $section)
                                            <option class="bg-light" value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_type_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Gender -->

                                <div class="form-group">
                                    <label for="gender">@lang('site.gender')</label>
                                    <select id="gender" class="form-control @error('gender') is-invalid @enderror"
                                        wire:model.defer="data.gender">
                                        <option hidden selected>@lang('site.gender')</option>
                                        @foreach ($genders as $gender)
                                            <option class="bg-light" value="{{ $gender['id'] }}">{{ $gender['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal User Password -->

                                <div class="form-group">
                                    <label for="password">@lang('site.password'){{ $showEditModal ? ' ( اختياري )' : '' }}</label>
                                    <input type="password" wire:model.defer="data.password"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        placeholder="@lang('site.enterPassword')">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>


                                <!-- Modal User Password Confirmation -->

                                <div class="form-group">
                                    <label for="passwordConfirmation">@lang('site.passwordConfirmation'){{ $showEditModal ? ' ( اختياري )' : '' }}</label>
                                    <input type="password" wire:model.defer="data.password_confirmation"
                                        class="form-control" id="passwordConfirmation"
                                        placeholder="@lang('site.enterConfirmPassword')">
                                </div>
                            </div>
                        </div>

                        <!-- Modal User Status -->
                        <div class="form-group clearfix">
                            <label for="statusRadio" class="d-inline">@lang('site.status') :</label>
                            <div class="icheck-primary d-inline ml-2 mr-2">
                                <input type="radio" id="radioPrimary1" wire:model="data.status" value="1">
                                <label for="radioPrimary1">@lang('site.active')</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioPrimary2" wire:model="data.status" value="0">
                                <label for="radioPrimary2">@lang('site.inActive')</label>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                                class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                        <button type="submit" class="btn btn-primary"><i class="mr-1 fa fa-save"></i>
                            @if ($showEditModal)
                            <span>@lang('site.saveChanges')</span>
                            @else
                            <span>@lang('site.save')</span>
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Show User -->

    <div class="modal fade" id="modal-show-user">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-body text-muted">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img src="{{ asset('backend/img/sweeklyplan.png') }}"
                                    class="profile-user-img img-fluid img-circle" alt="User profile picture">
                            </div>

                            <h3 class="text-center profile-username">{{ $data['name'] ?? '' }}</h3>

                            <ul class="mb-3 list-group list-group-unbordered" dir="rtl">
                                <li class="list-group-item">
                                    <b class="float-right">@lang('site.office') : </b> <a>{{ $data['office'] ?? ''
                                        }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b class="float-right">@lang('site.specialization') : </b> <a>{{
                                        $data['specialization'] ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b class="float-right">@lang('site.type') : </b> <a>{{ $data['job_type'] ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b class="float-right">@lang('site.sectionType') : </b> <a>{{ $data['section_type'] ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b class="float-right">@lang('site.email') :</b> <a>{{ $data['email'] ?? '' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b class="float-right">@lang('site.memberSince') :</b> <a>{{
                                        \Carbon\Carbon::parse($data['created_at'] ??
                                        '')->diff(\Carbon\Carbon::now())->format('%y سنه, %m شهر و %d يوم') }}</a>
                                </li>
                            </ul>

                            <button type="button" class="btn btn-primary btn-block"
                                data-dismiss="modal">@lang('site.close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete User -->

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'مستخدم'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteUser" class="btn btn-danger"><i
                            class="mr-1 fa fa-trash"></i>@lang('site.delete')</button>
                </div>
            </div>
        </div>
    </div>

    @section('script')

    {{-- <script src="{{ asset('backend/js/jquery.printPage.js') }}" type="text/javascript"></script> --}}

    <script>
        $(document).ready( function() {

                window.addEventListener('hide-form', function (event) {
                    $('#form').modal('hide');
                });

                window.addEventListener('show-form', function (event) {
                    $('#form').modal('show');
                });

                window.addEventListener('hide-modal-show', function (event) {
                    $('#modal-show-user').modal('hide');
                });

                window.addEventListener('show-modal-show', function (event) {
                    $('#modal-show-user').modal('show');
                });

                window.addEventListener('show-delete-modal', function (event) {
                    $('#confirmationModal').modal('show');
                });

                window.addEventListener('hide-delete-modal', function (event) {
                    $('#confirmationModal').modal('hide');
                });

                window.addEventListener('show-import-excel-modal', function (event) {
                    $('#importExcelModal').modal('show');
                });

                window.addEventListener('hide-import-excel-modal', function (event) {
                    $('#importExcelModal').modal('hide');
                });
            });
    </script>

    {{-- show-delete-alert-confirmation --}}

    <script>
        window.addEventListener('show-delete-alert-confirmation', event =>{
                Swal.fire({
                    title: '?@lang("site.delete")',
                    text: "@lang('site.deleteMessage')",
                    icon: 'warning',
                    iconHtml: '؟',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'لا',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('deleteConfirmed')
                    }
                })
            })
    </script>

    @endsection

</div>

