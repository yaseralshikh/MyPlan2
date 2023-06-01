<div>
    @section('style')
    <style>
        .disabled-link {
            cursor: default;
            pointer-events: none;
            text-decoration: none;
            color: rgb(174, 172, 172);
        }

        .draggable-mirror {
            background-color: white;
            width: 950px;
            display: flex;
            justify-content: space-between;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        }
    </style>
    @endsection

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">@lang('site.subTasks')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.subTasks')</li>
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
                        <button wire:click.prevent='addNewSubtask' class="ml-1 btn btn-sm btn-primary" {{  auth()->user()->hasPermission('subtasks-create') ? '' : 'disabled' }}>
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'مهمة فرعية'])</span>
                            </i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" {{  auth()->user()->hasPermission('subtasks-read') ? '' : 'disabled' }}>@lang('site.action')</button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                {{  auth()->user()->hasPermission('subtasks-read') ? '' : 'disabled' }}
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.setActive')</a>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.setInActive')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item  {{ $selectedRows && auth()->user()->hasPermission('subtasks-delete') ? 'text-danger' : 'disabled-link' }} delete-confirm"
                                    wire:click.prevent="deleteSelectedRows" href="#">@lang('site.deleteSelected')</a>
                            </div>
                        </div>
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
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
                                <option value="" selected>@lang('site.choise', ['name' => 'مكتب التعليم'])</option>
                                @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endrole

                        {{-- Education type Filter --}}
                        <div>
                            <select dir="rtl" name="section_type" wire:model="bySectionType" class="form-control form-control-sm mr-5">
                                <option value="" hidden selected>@lang('site.choise', [ 'name' => 'المرجع الإداري'])</option>
                                {{-- <option value="">@lang('site.all')</option> --}}
                                @foreach ($sectionTypes as $sectionType)
                                    <option class="bg-light" value="{{ $sectionType->id }}">{{ $sectionType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="flex-wrap">@lang('site.totalRecord', ['name' => 'المهام الفرعية']) : &nbsp{{ $subtasks->total() }}</label>
                        </div>

                    </div>

                    @if ($selectedRows)
                    <span class="mb-2 text-success">
                        <i class="fa fa-level" aria-hidden="true"></i>
                        selected
                        <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                        Str::plural('subtask', count($selectedRows)) }}
                        <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                            data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                    </span>
                    @endif

                    <div class="table-responsive" data-aos="fade-up" wire:ignore.self>
                        <table id="example2" class="table text-center table-hover sortable dtr-inline"
                            aria-describedby="example2_info">
                            <thead class="bg-light">
                                <tr>
                                    <td class="no-sort"></td>
                                    <td class="no-sort"scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                {{  auth()->user()->hasPermission('subtasks-read') ? '' : 'disabled' }}
                                                class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </td>
                                    <th class="no-sort">#</th>
                                    <th>@lang('site.subTask')</th>
                                    <th>@lang('site.section')</th>
                                    <th>@lang('site.sectionType')</th>
                                    <th>
                                        @lang('site.status')
                                    </th>
                                    <th colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody wire:sortable="updateSubtaskPosition">
                                @forelse ($subtasks as $subtask)
                                    <tr wire:sortable.item="{{ $subtask->id }}" wire:key="subtask-{{ $subtask->id }}" style="background-color: {{ $subtask->section == "مهمة فرعية" ? '#EFFBFB' : '#FBF8EF' }}">
                                        <td wire:sortable.handle style="width:10px; cursor: move;" width="10px"><i
                                                class="fa fa-arrows-alt text-muted"></i></td>
                                        <td scope="col">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" wire:model="selectedRows" value="{{ $subtask->id }}"
                                                    {{  auth()->user()->hasPermission('subtasks-read') ? '' : 'disabled' }}
                                                    class="custom-control-input" id="{{ $subtask->id }}">
                                                <label class="custom-control-label" for="{{ $subtask->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td dir="rtl" class="text-justify">{{ $subtask->name }}</td>
                                        <td dir="rtl" class="text-justify text-center">
                                            {{ $subtask->section }}
                                        </td>
                                        <td dir="rtl" class="text-justify text-center">
                                            {{ $subtask->section_type->name }}
                                        </td>
                                        <td>
                                            <span
                                                class="font-weight-bold badge text-center text-white {{ $subtask->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $subtask->status() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button wire:click.prevent="edit({{ $subtask }})"
                                                    class="btn btn-primary btn-sm" {{  auth()->user()->hasPermission('subtasks-update') ? '' : 'disabled' }}>
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button wire:click.prevent="confirmSubtaskRemoval({{ $subtask->id }})"
                                                    class="btn btn-danger btn-sm" {{  auth()->user()->hasPermission('subtasks-delete') ? '' : 'disabled' }}>
                                                    <i class="fa fa-trash bg-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">@lang('site.noDataFound')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $subtasks->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update Subtask -->

    <div class="modal fade" id="form" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateSubtask' : 'createSubtask' }}">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'مهمة قرعية / حاشية'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'مهمة قرعية / حاشية'])</span>
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

                                <!-- Modal Subtask name -->

                                <div class="form-group">
                                    <label for="name">@lang('site.subTask')</label>

                                    <textarea dir="rtl" wire:model.defer="data.name"
                                        class="text-justify form-control @error('name') is-invalid @enderror" rows="3"
                                        id="name" aria-describedby="nameHelp"
                                        dir="rtl" placeholder="@lang('site.enterFieldName', ['name' => 'المهمة الفرعية / الحاشية'])"></textarea>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Subtask section -->

                                <div class="form-group">
                                    <label for="section">@lang('site.section')</label>
                                    <select id="section" class="form-control @error('section') is-invalid @enderror"
                                        wire:model.defer="data.section">
                                        <option hidden>@lang('site.choise', ['name' => 'القسم'])</option>
                                        @foreach ($sections as $section)
                                        <option class="bg-light" value="{{ $section['name'] }}">{{ $section['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Subtask section_type_id -->

                                <div class="form-group">
                                    <label for="section_type">@lang('site.sectionType')</label>
                                    <select id="section_type" class="form-control @error('section_type_id') is-invalid @enderror"
                                        wire:model.defer="data.section_type_id">
                                        <option hidden selected>@lang('site.choise', ['name' => 'المرجع الإداري'])</option>
                                        @foreach ($sectionTypes as $sectionType)
                                            <option class="bg-light" value="{{ $sectionType->id }}">{{ $sectionType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_type_id')
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

    <!-- Modal Delete Subtask -->

    <div class="modal fade" id="confirmationModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'مهمة فرعية'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteSubtask" class="btn btn-danger"><i
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
