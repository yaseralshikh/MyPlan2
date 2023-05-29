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
                    <h1 class="m-0">@lang('site.semesters')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.semesters')</li>
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
                        <button wire:click.prevent='addNewSemester' class="ml-1 btn btn-sm btn-primary" {{  auth()->user()->hasPermission('semesters-create') ? '' : 'disabled' }}>
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'فصل دراسي'])</span>
                            </i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" {{  auth()->user()->hasPermission('semesters-read') ? '' : 'disabled' }}>@lang('site.action')</button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                {{  auth()->user()->hasPermission('semesters-read') ? '' : 'disabled' }}
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                {{-- <a class="dropdown-item" wire:click.prevent="exportExcel" href="#"
                                    aria-disabled="true">Export to Excel</a> --}}
                                {{-- <a class="dropdown-item" wire:click.prevent="exportPDF" href="#">Export to PDF</a>
                                --}}
                                {{-- <div class="dropdown-divider"></div> --}}
                                {{-- @if ($selectedRows) --}}
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.setActive')</a>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.setInActive')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item {{ $selectedRows && auth()->user()->hasPermission('semesters-delete') ? 'text-danger' : 'disabled-link' }}  delete-confirm"
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
                            <input dir="rtl" type="search" wire:model="searchTerm" class="form-control" placeholder="@lang('site.searchFor')..." value="">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:model="byStatus" class="custom-control-input"
                                id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1">@lang('site.activeSemesters')</label>
                        </div>

                        <label class="flex-wrap">@lang('site.totalRecord', ['name' => 'الفصول الدراسية']) : &nbsp{{ $semesters->total() }}</label>

                    </div>

                    @if ($selectedRows)
                    <span class="mb-2 text-success">
                        <i class="fa fa-level" aria-hidden="true"></i>
                        selected
                        <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                        Str::plural('semester', count($selectedRows)) }}
                        <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                            data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                    </span>
                    @endif

                    <div class="table-responsive" data-aos="fade-up" wire:ignore.self>
                        <table id="example2" class="table text-center table-bordered table-hover sortable dtr-inline"
                            aria-describedby="example2_info">
                            <thead class="bg-light">
                                <tr>
                                    <td scope="col" class="align-middle no-sort">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </td>
                                    <th class="align-middle no-sort">#</th>
                                    <th>
                                        @lang('site.semester')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.start')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.end')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.schoolYear')
                                    </th>
                                    <th class="align-middle no-sort">
                                        @lang('site.currently')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.status')
                                    </th>
                                    <th class="align-middle" colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($semesters as $semester)
                                <tr>
                                    <td class="align-middle" scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectedRows" value="{{ $semester->id }}"
                                                class="custom-control-input" id="{{ $semester->id }}">
                                            <label class="custom-control-label" for="{{ $semester->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $semester->name }}</td>
                                    <td class="align-middle" data-sort="{{ Carbon\Carbon::parse($semester->start)->format('Ymd') }}">
                                        {{ $semester->start }}<br>
                                        {{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d', $semester->start) }}
                                    </td>
                                    <td class="align-middle" data-sort="{{ Carbon\Carbon::parse($semester->end)->format('Ymd') }}">
                                        {{ $semester->end }}<br>
                                        {{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d', $semester->end) }}
                                    </td>
                                    <td class="align-middle">{{ $semester->school_year }}</td>
                                    <td class="align-middle">
                                        <div class="form-check">
                                            <input wire:change="changeActive({{ $semester->id }})"
                                                class="form-check-input" type="checkbox" {{ $semester->active ?
                                            'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="font-weight-bold badge text-white {{ $semester->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $semester->status() }}
                                        </span>
                                    </td>
                                    <td class="align-middle no-sort">
                                        <div class="btn-group btn-group-sm">
                                            <button wire:click.prevent="edit({{ $semester }})"
                                                class="btn btn-primary btn-sm" {{  auth()->user()->hasPermission('semesters-update') ? '' : 'disabled' }}>
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button wire:click.prevent="confirmSemesterRemoval({{ $semester->id }})"
                                                class="btn btn-danger btn-sm" {{  auth()->user()->hasPermission('semesters-delete') ? '' : 'disabled' }}>
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
                            {{-- <tfoot>
                                <tr>
                                    <td colspan="5">
                                        {!! $semesters->appends(request()->all())->links() !!}
                                    </td>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $semesters->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update semester -->

    <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateSemester' : 'createSemester' }}">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'فصل دراسي'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'فصل دراسي'])</span>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">
                                <!-- Modal Semester Name -->
                                <div class="form-group">
                                    <label for="name">@lang('site.semester')</label>
                                    <input type="text" wire:model.defer="data.name"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        aria-describedby="nameHelp" dir="rtl" placeholder="@lang('site.enterFieldName', ['name' => 'عنوان الفصل الدراسي'])">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <!-- Modal Semester Start -->
                                <div class="form-group">
                                    <label for="start">@lang('site.start')</label>
                                    <input type="date" wire:model.defer="data.start"
                                        class="form-control @error('start') is-invalid @enderror" id="start"
                                        aria-describedby="startHelp" dir="rtl">
                                    @error('start')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <!-- Modal Semester End -->
                                <div class="form-group">
                                    <label for="end">@lang('site.end')</label>
                                    <input type="date" wire:model.defer="data.end"
                                        class="form-control @error('end') is-invalid @enderror" id="end"
                                        aria-describedby="endHelp" dir="rtl">
                                    @error('end')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <!-- Modal school_year -->
                                <div class="form-group">
                                    <label for="school_year">@lang('site.schoolYear')</label>
                                    <input type="number" wire:model.defer="data.school_year"
                                        class="form-control @error('school_year') is-invalid @enderror" id="school_year"
                                        aria-describedby="school_yearHelp" dir="rtl" placeholder="@lang('site.enterFieldName', ['name' => 'العام الدراسي'])">
                                    @error('school_year')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                {{--
                                <!-- Modal Active -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input wire:model.defer="data.active"
                                            class="custom-control-input @error('active') is-invalid @enderror"
                                            type="checkbox" id="customCheckbox1">
                                        <label for="customCheckbox1" class="custom-control-label">Active</label>
                                        @error('school_year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div> --}}
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

    <!-- Modal Delete Semester -->

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'فصل دراسي'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteSemester" class="btn btn-danger"><i
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
