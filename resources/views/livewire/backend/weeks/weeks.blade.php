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
                    <h1 class="m-0">@lang('site.weeks')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.weeks')</li>
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
                        <button wire:click.prevent='addNewWeek' class="ml-1 btn btn-sm btn-primary " {{
                            auth()->user()->hasPermission('weeks-create') ? '' : 'disabled' }}>
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'أسبوع دراسي'])</span>
                            </i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" {{ auth()->user()->hasPermission('weeks-read') ? '' : 'disabled' }}>@lang('site.action')</button>
                            <button type="button"
                                class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" {{  auth()->user()->hasPermission('weeks-read') ? '' : 'disabled' }}
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.setActive')</a>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.setInActive')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item {{ $selectedRows && auth()->user()->hasPermission('weeks-delete') ? 'text-danger' : 'disabled-link' }}  delete-confirm"
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

                        {{-- Semester Filter --}}
                        <div>
                            <select dir="rtl" wire:model="bySemester" class="form-control form-control-sm mr-5">
                                <option value="" selected>@lang('site.choise', ['name' => 'ألفصل الدراسي'])</option>
                                @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}" style="{{
                                    $semester->active ? 'color: blue; background:#F2F2F2;' : '' }}">{{ $semester->name
                                    }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:model="byStatus" class="custom-control-input"
                                id="customSwitch1" {{  auth()->user()->hasPermission('weeks-read') ? '' : 'disabled' }}>
                            <label class="custom-control-label" for="customSwitch1">@lang('site.activeWeeks')</label>
                        </div>

                        <label class="flex-wrap">@lang('site.totalRecord', ['name' => 'الأسابيع']) : &nbsp{{ $weeks->total() }}</label>

                    </div>

                    @if ($selectedRows)
                    <span class="mb-2 text-success">
                        <i class="fa fa-task" aria-hidden="true"></i>
                        selected
                        <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                        Str::plural('week', count($selectedRows)) }}
                        <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                            data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                    </span>
                    @endif

                    <div class="table-responsive" data-aos="fade-up" wire:ignore.self>
                        <table id="example2" class="table text-center table-bordered table-hover sortable dtr-inline"
                            aria-describedby="example2_info">
                            <thead class="bg-light">
                                <tr>
                                    <td class="no-sort" scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                class="custom-control-input" id="customCheck" {{
                                                 auth()->user()->hasPermission('weeks-read') ? '' : 'disabled' }}>
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </td>
                                    <th class="no-sort">#</th>
                                    <th>
                                        @lang('site.schoolWeek')
                                    </th>
                                    <th>
                                        @lang('site.start')
                                    </th>
                                    <th>
                                        @lang('site.end')
                                    </th>
                                    <th>
                                        @lang('site.semester')
                                    </th>
                                    <th>
                                        @lang('site.schoolYear')
                                    </th>
                                    <th>
                                        @lang('site.status')
                                    </th>
                                    <th colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($weeks as $week)
                                <tr>
                                    <td scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectedRows" value="{{ $week->id }}"
                                                class="custom-control-input" id="{{ $week->id }}" {{
                                                 auth()->user()->hasPermission('weeks-read') ? '' : 'disabled' }}>
                                            <label class="custom-control-label" for="{{ $week->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td tabindex="0">{{ $week->name }}</td>
                                    <td data-sort="{{ Carbon\Carbon::parse($week->start)->format('Ymd') }}">
                                        {{ $week->start }}<br>
                                        {{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d', $week->start) }}
                                    </td>
                                    <td data-sort="{{ Carbon\Carbon::parse($week->end)->format('Ymd') }}">
                                        {{ $week->end }}<br>
                                        {{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d', $week->end) }}
                                    </td>
                                    <td>{{ $week->semester->name }}</td>
                                    <td>{{ $week->semester->school_year }}</td>
                                    <td>
                                        <span
                                            class="font-weight-bold badge text-white {{ $week->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $week->status() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button wire:click.prevent="edit({{ $week }})"
                                                class="btn btn-primary btn-sm" {{  auth()->user()->hasPermission('weeks-update') ? '' : 'disabled' }}>
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button wire:click.prevent="confirmWeekRemoval({{ $week->id }})"
                                                class="btn btn-danger btn-sm" {{ auth()->user()->hasPermission('weeks-delete') ? '' : 'disabled' }}>
                                                <i class="fa fa-trash bg-danger"></i>
                                            </button>
                                        </div>
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
                                        {!! $weeks->appends(request()->all())->links() !!}
                                    </td>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $weeks->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update Week -->

    <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateWeek' : 'createWeek' }}">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'أسبوع دراسي'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'أسبوع دراسي'])</span>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">

                                <!-- Modal Week semester -->

                                <div class="form-group">
                                    <label for="semester_id">@lang('site.semester')</label>
                                    <select id="semester_id"
                                        class="form-control @error('semester_id') is-invalid @enderror"
                                        wire:model.defer="data.semester_id">
                                        <option hidden>@lang('site.choise', ['name' => 'الفصل الدراسي'])</option>
                                        @foreach ($semesters as $semester)
                                        <option class="bg-light" value="{{ $semester->id }}">{{ $semester->name . ' - '
                                            . $semester->school_year }}</option>
                                        @endforeach
                                    </select>
                                    @error('semester_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Week Name -->

                                <div class="form-group">
                                    <label for="name">@lang('site.name')</label>
                                    <input type="text" wire:model.defer="data.name"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        aria-describedby="titleHelp" dir="rtl" placeholder="@lang('site.enterFieldName', ['name' => 'الأسبوع الدراسي مثال : ف2 - الأسبوع الأول'])">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Week start -->

                                <div class="form-group">
                                    <label for="start">@lang('site.start')</label>
                                    <input type="date" wire:model.defer="data.start"
                                        class="form-control @error('start') is-invalid @enderror" id="start"
                                        aria-describedby="startHelp" placeholder="Enter week start">
                                    @error('start')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Week end -->

                                <div class="form-group">
                                    <label for="end">@lang('site.end')</label>
                                    <input type="date" wire:model.defer="data.end"
                                        class="form-control @error('end') is-invalid @enderror" id="end"
                                        aria-describedby="endHelp" placeholder="Enter week end">
                                    @error('end')
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

    <!-- Modal Delete Week -->

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'أسبوع دراسي'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteWeek" class="btn btn-danger"><i
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
                    $('#modal-show-week').modal('hide');
                });
                window.addEventListener('show-modal-show', function (event) {
                    $('#modal-show-week').modal('show');
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
