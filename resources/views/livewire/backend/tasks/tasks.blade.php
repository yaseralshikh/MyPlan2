<div>
    @section('style')
    <style>
        .disabled-link {
            cursor: default;
            pointer-events: none;
            text-decoration: none;
            color: rgb(174, 172, 172);
        }
        .sortable th.no-sort {
        pointer-events: none;
        }

    </style>
    @endsection

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">@lang('site.tasks')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.tasks')</li>
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
                        <button wire:click.prevent='addNewTask' class="ml-1 btn btn-sm btn-primary" {{  auth()->user()->hasPermission('tasks-create') ? '' : 'disabled' }}>
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'مهمة'])</span>
                            </i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" {{  auth()->user()->hasPermission('tasks-read') ? '' : 'disabled' }}>@lang('site.action')</button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                {{  auth()->user()->hasPermission('tasks-read') ? '' : 'disabled' }}
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.setActive')</a>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.setInActive')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item {{  auth()->user()->hasPermission('tasks-delete') ? '' : 'disabled' }} delete-confirm"
                                    wire:click.prevent="deleteSelectedRows" href="#">@lang('site.deleteSelected')</a>
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
                            <input dir="rtl" type="search" wire:model="searchTerm" class="form-control" placeholder="@lang('site.searchFor')..." value="">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- offices Filter --}}
                        @role('superadmin|operationsmanager')
                        <div>
                            <select dir="rtl" name="office_id" wire:model="byOffice"
                                class="form-control form-control-sm">
                                <option value="" hidden>@lang('site.choise', ['name' => 'مكتب التعليم'])</option>
                                @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endrole

                        {{-- levels Filter --}}
                        <div>
                            <select dir="rtl" name="level_id" wire:model="byLevel"
                                class="form-control form-control-sm">
                                <option value="" selected>@lang('site.choise', ['name' => 'المرحلة'])</option>
                                @foreach ($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="flex-wrap">@lang('site.totalRecord', ['name' => 'المهام']) : &nbsp{{ $tasks->total() }}</label>
                        </div>

                    </div>

                    @if ($selectedRows)
                    <span class="mb-2 text-success">
                        <i class="fa fa-task" aria-hidden="true"></i>
                        selected
                        <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                        Str::plural('task', count($selectedRows)) }}
                        <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                            data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                    </span>
                    @endif

                    <div class="table-responsive" data-aos="fade-up" wire:ignore.self>
                        <table id="example2" class="table text-center table-bordered table-hover dtr-inline sortable"
                            aria-describedby="example2_info">
                            <thead class="bg-light">
                                <tr>
                                    <td class="align-middle no-sort" scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </td>
                                    <th class="align-middle no-sort">#</th>
                                    <th class="align-middle">
                                        @lang('site.task')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.level')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.eventsCount')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.needCare')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.status')
                                    </th>
                                    <th class="align-middle no-sort" colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tasks as $task)
                                <tr>
                                    <td scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectedRows" value="{{ $task->id }}"
                                                class="custom-control-input" id="{{ $task->id }}">
                                            <label class="custom-control-label" for="{{ $task->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->level->name }}</td>
                                    <td>{{ $task->events()->count() }}</td>
                                    <td class="align-middle">
                                        <div class="form-check">
                                            <input wire:change="changeNeedCare({{ $task->id }})"
                                                class="form-check-input" type="checkbox" {{ $task->need_care ?
                                            'checked' : '' }} {{ $task->level_id <> 7 ? '' : 'disabled' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="font-weight-bold badge text-white {{ $task->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $task->status() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button wire:click.prevent="edit({{ $task }})"
                                                class="btn btn-primary btn-sm" {{  auth()->user()->hasPermission('tasks-update') ? '' : 'disabled' }}><i class="fa fa-edit"></i></button>
                                            <button wire:click.prevent="confirmTaskRemoval({{ $task->id }})"
                                                class="btn btn-danger btn-sm" {{  auth()->user()->hasPermission('tasks-delete') ? '' : 'disabled' }}><i
                                                    class="fa fa-trash bg-danger"></i></button>
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">@lang('site.noDataFound')</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $tasks->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update Task -->

    <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateTask' : 'createTask' }}">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'مهمة'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'مهمة'])</span>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">

                                <!-- Modal Office -->
                                @role('superadmin|operationsmanager')
                                <div class="form-group">
                                    <label for="office_id">@lang('site.office')</label>
                                    <select id="office_id" class="form-control @error('office_id') is-invalid @enderror"
                                        wire:model.defer="data.office_id"
                                        wire:change="OfficeOption($event.target.value)">
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

                                <!-- Modal Task Full Name -->

                                <div class="form-group">
                                    <label for="name">@lang('site.task')</label>
                                    <input type="text" wire:model.defer="data.name"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        aria-describedby="nameHelp" dir="rtl" placeholder="@lang('site.enterFieldName', ['name' => 'اسم المهمة'])">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Task Type -->

                                <div class="form-group">
                                    <label for="level_id">@lang('site.level')</label>
                                    <select id="level_id"
                                        class="form-control @error('level_id') is-invalid @enderror"
                                        wire:model.defer="data.level_id">
                                        <option hidden>@lang('site.choise', ['name' => 'المرحلة'])</option>
                                        @foreach ($levels as $level)
                                        <option class="bg-light" value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('level_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
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

    <!-- Modal Delete Task -->

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'مهمة'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteTask" class="btn btn-danger"><i
                            class="mr-1 fa fa-trash"></i>@lang('site.delete')</button>
                </div>
            </div>
        </div>
    </div>

    @section('script')

    <script>
        $(document).ready( function() {

                window.addEventListener('hide-form', function (event) {
                    $('#form').modal('hide');
                });

                window.addEventListener('show-form', function (event) {
                    $('#form').modal('show');
                });

                window.addEventListener('hide-modal-show', function (event) {
                    $('#modal-show-task').modal('hide');
                });

                window.addEventListener('show-modal-show', function (event) {
                    $('#modal-show-task').modal('show');
                });

                window.addEventListener('show-delete-modal', function (event) {
                    $('#confirmationModal').modal('show');
                });

                window.addEventListener('hide-delete-modal', function (event) {
                    $('#confirmationModal').modal('hide');
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
