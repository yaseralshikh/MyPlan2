<div>
    @section('style')
    <style>
        body {
            font-size: 14px;
        }

        .disabled-link {
            cursor: default;
            pointer-events: none;
            text-decoration: none;
            color: rgb(174, 172, 172);
        }

        .hover-item:hover {
            background-color: rgb(174, 172, 172);
        }

        #event-table th,
        #event-table td {
            border: 2px 1px solid #ddd;
            padding: 8px;
        }
    </style>
    @endsection

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">@lang('site.events')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.events')</li>
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
                        <button wire:click.prevent='addNewEvent' class="ml-1 btn btn-sm btn-primary">
                            {{ auth()->user()->hasPermission('events-create') ? '' : 'disabled' }}
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'خطة'])</span>
                            </i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm" {{
                                auth()->user()->hasPermission('events-read') ? '' : 'disabled'
                                }}>@lang('site.action')</button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" {{
                                auth()->user()->hasPermission('events-read') ? '' : 'disabled' }}
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                {{-- user dose not have Plan --}}
                                <a class="dropdown-item" wire:click.prevent="ShowModalUsersPlansIncomplete"
                                    href="#">@lang('site.userWithoutPlan')</a>
                                {{-- <a class="dropdown-item" wire:click.prevent="UsersPlansIncomplete"
                                    href="#">@lang('site.userWithoutPlan')</a> --}}
                                {{-- School dose not have Plan in this week --}}
                                <a class="dropdown-item" wire:click.prevent="ShowModalSchoolsWithNoVisits"
                                    href="#">@lang('site.taskWithoutPlan')</a>
                                <div class="dropdown-divider"></div>

                                {{-- export data to Excel file --}}
                                <a dir="rtl" class="dropdown-item" wire:click.prevent="exportExcel" href="#"
                                    aria-disabled="true">@lang('site.exportExcel')</a>
                                {{-- export data to PDF file --}}
                                <a dir="rtl" class="dropdown-item" wire:click.prevent="exportPDF"
                                    href="#">@lang('site.exportPDF')</a>
                                {{-- <a dir="rtl" class="dropdown-item" target="_blank"
                                    href="https://www.ilovepdf.com/merge_pdf"
                                    aria-disabled="true">@lang('site.merge_pdf')</a> --}}
                                <div class="dropdown-divider"></div>

                                {{-- set event as active --}}
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.eventsActive')</a>
                                {{-- set event as InActive --}}
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.eventsInActive')</a>
                                <div class="dropdown-divider"></div>

                                {{-- set event as done --}}
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsDone" href="#">@lang('site.eventsDone')</a>
                                {{-- set event as not Done --}}
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsUnDone" href="#">@lang('site.eventsUnDone')</a>
                                <div class="dropdown-divider"></div>

                                {{-- Delete Selected event --}}
                                <a class="dropdown-item {{ $selectedRows && auth()->user()->hasPermission('events-delete') ? 'text-danger' : 'disabled-link' }}  delete-confirm"
                                    wire:click.prevent="deleteSelectedRows" href="#">@lang('site.deleteSelected')</a>
                            </div>
                        </div>
                    </h3>

                    <div class="card-tools">
                        <div class="btn-group pr-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" wire:model="allowed_create_plans"
                                    wire:change='update_allowed_create_plans' class="custom-control-input"
                                    id="customSwitchallowed_create_plans">
                                <label class="custom-control-label"
                                    for="customSwitchallowed_create_plans">@lang('site.allowed_create_plans')</label>
                            </div>
                            <a class="btn btn-default btn-sm ml-3"
                                onclick="window.scrollTo(0, document.body.scrollHeight)">
                                <i class="fas fa-arrow-circle-down"></i>
                            </a>
                            {{-- <a href="#" class="btn btn-outline-secondary btn-sm hover-item" data-toggle="tooltip"
                                data-placement="top" title="@lang('site.exportExcel')" wire:click.prevent="exportExcel">
                                <i class="fa fa-file-excel text-success"></i>
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm hover-item" data-toggle="tooltip"
                                data-placement="top" title="@lang('site.exportPDF')" wire:click.prevent="exportPDF">
                                <i class="fa fa-file-pdf text-danger"></i>
                            </a>
                            <a href="#"
                                class="btn btn-outline-secondary btn-sm hover-item {{ $selectedRows ? '' : 'disabled' }}"
                                data-toggle="tooltip" data-placement="top" title="@lang('site.eventsAcive')"
                                wire:click.prevent="setAllAsActive">
                                <i class="fa fa-regular fa-thumbs-up text-success"></i>
                            </a>
                            <a href="#"
                                class="btn btn-outline-secondary btn-sm hover-item {{ $selectedRows ? '' : 'disabled' }}"
                                data-toggle="tooltip" data-placement="top" title="@lang('site.eventsInAcive')"
                                wire:click.prevent="setAllAsInActive">
                                <i class="fa fa-solid fa-thumbs-down text-dark"></i>
                            </a>
                            <a href="#"
                                class="btn bg-danger text-white btn-sm hover-item {{ $selectedRows ? '' : 'disabled' }} delete-confirm"
                                data-toggle="tooltip" data-placement="top" title="@lang('site.deleteSelected')"
                                wire:click.prevent="deleteSelectedRows">
                                <i class="fa fa-duotone fa-trash"></i>
                            </a> --}}
                        </div>

                        {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group d-flex justify-content-between align-items-center">
                        {{-- search --}}
                        <div class="input-group" style="width: 200px;">
                            <input dir="rtl" type="search" wire:model.debounce.350ms="searchTerm"
                                class="form-control form-control-sm" placeholder="@lang('site.searchFor')..." value="">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default btn-sm">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- OrderBy Filter --}}
                        <div>
                            <select dir="rtl" wire:model="byOrderBy" class="form-control form-control-sm">
                                <option disabled selected>@lang('site.choise', [ 'name' => 'الفرز المناسب'])</option>
                                <option value="user_id" selected>@lang('site.name')</option>
                                <option value="task_id" selected>@lang('site.task')</option>
                                <option value="note" selected>@lang('site.note')</option>
                                <option value="start" selected>@lang('site.date')</option>
                                <option value="week_id" selected>@lang('site.schoolWeek')</option>
                                <option value="created_at" selected>@lang('site.createdAt')</option>
                                <option value="status" selected>@lang('site.status')</option>
                                <option value="task_done" selected>@lang('site.taskDone')</option>
                            </select>
                        </div>

                        {{-- Paginate Filter --}}
                        <div>
                            <select dir="rtl" wire:model="paginateValue" class="form-control form-control-sm">
                                <option value="50" selected>50</option>
                                <option value="100" selected>100</option>
                                <option value="150" selected>150</option>
                                <option value="100000" selected>@lang('site.all')</option>
                            </select>
                        </div>

                        {{-- Week Filter --}}
                        <div>
                            <select dir="rtl" name="week_id" wire:model="byWeek"
                                wire:click="resetSelectedRows" class="form-control form-control-sm mr-5">
                                <option value="" selected>@lang('site.choise', [ 'name' => 'الأسبوع الدراسي'])</option>
                                @foreach ($weeks as $week)
                                <option value="{{ $week->id }}" {{ $week->active ? 'selected' : '' }} style="{{
                                    $week->active ? 'color: blue; background:#F2F2F2;' : '' }}">{{ $week->name . ' (
                                    '.$week->semester->school_year . ' )' }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Section type Filter --}}
                        <div>
                            <select dir="rtl" name="bySectionType" wire:model="bySectionType"
                                class="form-control form-control-sm mr-5" wire:click="resetSelectedRows">
                                <option value="" selected>@lang('site.choise', [ 'name' => 'المرجع الإداري'])</option>
                                @foreach ($sctionsType as $sctionType)
                                <option class="bg-light" value="{{ $sctionType->id }}">{{ $sctionType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @role('superadmin|operationsmanager')
                        {{-- offices Filter --}}
                        <div>
                            <select dir="rtl" name="office_id" wire:model="byOffice"
                                class="form-control form-control-sm" wire:click="resetSelectedRows">
                                <option value="" selected>@lang('site.choise', ['name' => 'مكتب التعليم'])</option>
                                @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- byGender Filter --}}
                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:model="byGender" wire:click="resetSelectedRows" class="custom-control-input"
                                id="customSwitchByGender">
                            <label dir="rtl" class="custom-control-label"
                                for="customSwitchByGender">@lang('site.gender') ( بنين ) </label>
                        </div>
                        @endrole

                        {{-- Status Filter --}}
                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:model="byStatus" wire:click="resetSelectedRows" class="custom-control-input"
                                id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1">@lang('site.activeEvents')</label>
                        </div>

                        {{-- Total Events --}}
                        <div>
                            <label class="flex-wrap">@lang('site.totalRecord', ['name' => 'الخطط']) : &nbsp( {{
                                $events->total() }} )</label>
                        </div>
                    </div>

                    @if ($selectedRows)
                    <span class="mb-2 text-success">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        selected
                        <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                        Str::plural('event', count($selectedRows)) }}
                        <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                            data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                    </span>
                    @endif

                    <div class="table-responsive" wire:ignore.self>
                        <table id="event-table" class="table text-center table-bordered table-hover dtr-inline sortable"
                            aria-describedby="event-table">
                            <thead class="bg-light ">
                                <tr>
                                    <td scope="col" class="no-sort">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </td>
                                    <th class="no-sort">#</th>
                                    <th>
                                        @lang('site.name')
                                    </th>
                                    <th>
                                        @lang('site.specialization')
                                    </th>
                                    <th>
                                        @lang('site.sectionType')
                                    </th>
                                    <th>
                                        @lang('site.task')
                                    </th>
                                    <th>
                                        @lang('site.note')
                                    </th>
                                    <th>
                                        @lang('site.day')
                                    </th>
                                    <th>
                                        @lang('site.date')
                                    </th>
                                    <th>
                                        @lang('site.schoolWeek')
                                    </th>
                                    <th>
                                        @lang('site.createdAt')
                                    </th>
                                    <th>
                                        @lang('site.status')
                                    </th>
                                    <th>
                                        @lang('site.taskDone')
                                    </th>
                                    <th class="no-sort" colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                <tr>
                                    {{-- <td class="align-middle" scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectedRows" value="{{ $event->id }}"
                                                class="custom-control-input" id="{{ $event->id }}">
                                            <label class="custom-control-label" for="{{ $event->id }}"></label>
                                        </div>
                                    </td> --}}

                                    <td class="align-middle" scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:click="toggleRow('{{ $event->id }}')"
                                                {{ in_array($event->id, $selectedRows) ? 'checked' : '' }}
                                                class="custom-control-input" id="{{ $event->id }}">
                                            <label class="custom-control-label" for="{{ $event->id }}"></label>
                                        </div>
                                    </td>

                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="dtr-control align-middle">{{ $event->user->name }}</td>
                                    <td class="align-middle">{{ $event->user->specialization->name }}</td>
                                    <td class="align-middle">{{ $event->user->section_type->name }}</td>
                                    <td class="align-middle" style="background-color: {{ $event->color }};">{{
                                        $event->task->name }}</td>
                                    <td class="align-middle">{{ $event->note }}</td>
                                    <td class="align-middle"
                                        data-sort="{{ Carbon\Carbon::parse($event->start)->format('Ymd') }}">
                                        {{ Alkoumi\LaravelHijriDate\Hijri::Date('l', $event->start) }}
                                    </td>
                                    <td class="align-middle"
                                        data-sort="{{ Carbon\Carbon::parse($event->start)->format('Ymd') }}">
                                        {{ $event->start }} <br>
                                        {{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d', $event->start) }}
                                    </td>
                                    {{-- <td>{{
                                        (Carbon\Carbon::parse($event->end))->diffInDays(Carbon\Carbon::parse($event->start))
                                        }}</td> --}}
                                    <td class="align-middle">{{ $event->week->name . ' ( ' .
                                        $event->week->semester->school_year . ' )' }}
                                    </td>
                                    <td class="align-middle"
                                        data-sort="{{ Carbon\Carbon::parse($event->created_at)->format('d/m/Y h:i A') }}">
                                        {{ Carbon\Carbon::parse($event->created_at)->format('d/m/Y') }} <br>
                                        {{ Carbon\Carbon::parse($event->created_at)->format('h:i A') }}
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="font-weight-bold badge text-white {{ $event->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $event->status() }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="font-weight-bold badge text-white {{ $event->task_done == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $event->task_done() }}
                                        </span>
                                    </td>
                                    {{-- <td>{{ Alkoumi\LaravelHijriDate\Hijri::Date('l', $event->created_at) }}<br>
                                        {{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d', $event->created_at) }}<br>
                                        {{ Carbon\Carbon::parse($event->created_at)->toDateString() }}
                                    </td> --}}
                                    <td class="align-middle">
                                        <div class="btn-group btn-group-sm">
                                            <button wire:click.prevent="edit({{ $event }})"
                                                class="btn btn-primary btn-sm" {{
                                                auth()->user()->hasPermission('events-update') ? '' : 'disabled' }}>
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button wire:click.prevent="confirmEventRemoval({{ $event->id }})"
                                                class="btn btn-danger btn-sm" {{
                                                auth()->user()->hasPermission('events-delete') ? '' : 'disabled' }}>
                                                <i class="fa fa-trash bg-danger"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="14" class="text-center">@lang('site.noDataFound')</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        <a class="btn btn-default btn-sm" onclick="window.scrollTo(0, 0)">
                            <i class="fas fa-arrow-alt-circle-up"></i>
                        </a>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $events->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update Event -->

    <div class="modal fade" tabindex="-1" id="form" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateEvent' : 'createEvent' }}">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'خطة'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'خطة'])</span>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif --}}

                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">
                                <!-- Modal user_id -->
                                <div class="form-group" wire:ignore.self>
                                    <label for="user_id" class="form-label">{{ __('site.userName') }} :</label>

                                    <select name="user_id" wire:model.defer="data.user_id"
                                        class="form-control @error('user_id') is-invalid @enderror" id="user_id">
                                        <option value="" selected>@lang('site.choise', ['name' => 'المشرف التربوي'])
                                        </option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('user_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <!-- Modal Office -->
                                @if(auth()->user()->office->office_type == 0)
                                <div class="form-group mb-3" wire:ignore.self>
                                    <label for="office_id" class="col-form-label">@lang('site.offices') :</label>
                                    <select wire:model.defer="data.office_id" id="office_id"
                                        wire:change="OfficeOption($event.target.value)"
                                        class="form-control fw-bold @error('office_id') is-invalid @enderror">
                                        <option value="" hidden selected>@lang('site.choise', ['name' => 'مكتب التعليم /
                                            إدارة']) :</option>
                                        @foreach ($offices->whereIn('id', array_merge($education_offices,
                                        [auth()->user()->office->id])) as $office)
                                        <option class="fw-bold {{ $loop->last ? 'bg-body-tertiary text-primary' : '' }}"
                                            value="{{ $office->id }}">{{ $office->name }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('office_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @endif

                                <!-- Modal Levels -->
                                <div class="form-group mb-3" wire:ignore.self>
                                    <label for="level_id" class="col-form-label">@lang('site.level') :</label>
                                    <select wire:model.defer="data.level_id"
                                        wire:change="LevelOption($event.target.value)" id="level_id"
                                        class="form-control @error('level_id') is-invalid @enderror">
                                        <option value="" selected>@lang('site.choise', ['name' => 'المرحلة']) :</option>
                                        @foreach ($levels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('level_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal Task (Event Title) -->

                                <div class="form-group" wire:ignore.self>
                                    <label for="task_id" class="col-form-label">@lang('site.task') :</label>
                                    <select wire:model.defer="data.task_id" id="task_id"
                                        class="form-control select2bs4 @error('task_id') is-invalid @enderror"
                                        id="task_id">
                                        <option value="" selected>@lang('site.choise', ['name' => 'المهمة'])</option>
                                        @foreach ($tasks as $task)
                                        <option value="{{ $task->id }}">{{ $task->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('task_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>

                                <!-- Modal Task ( Note ) -->
                                <div dir="" class="form-group mb-3" wire:ignore.self>
                                    <label for="note" class="col-form-label">@lang('site.note') :</label>

                                    <textarea dir="rtl" wire:model.defer="data.note"
                                        class="text-justify form-control @error('note') is-invalid @enderror" rows="3"
                                        id="note" aria-describedby="noteHelp" dir="rtl"
                                        placeholder="@lang('site.notePlaceholder')">
                                    </textarea>

                                    @error('note')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <!-- Modal Event Start -->

                                <div class="form-group" wire:ignore.self>
                                    <label for="start">@lang('site.date') :</label>
                                    <input type="date" wire:model.defer="data.start"
                                        class="form-control @error('start') is-invalid @enderror" id="start"
                                        aria-describedby="startHelp" placeholder="Enter start">

                                    @error('start')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Modal Event Status -->
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

                        <!-- Modal Event week_id -->
                        <div class="mb-3">
                            <input type="hidden" wire:model.defer="data.week_id"
                                class="form-control @error('week_id') is-invalid @enderror" id="week_id">

                            @error('week_id')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
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

    <!-- Modal Delete Event -->

    <div dir="rtl" class="modal fade" id="confirmationModal" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'خطة'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteEvent" class="btn btn-danger"><i
                            class="mr-1 fa fa-trash"></i>@lang('site.delete')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal show event overlap confirmation -->

    <div dir="rtl" class="modal fade" id="confirmationEventOverLapModal" data-keyboard="false" data-backdrop="static"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.event-overlap-confirmation')</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.event-overlap-message')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="confirmEventOverLap" class="btn btn-success"><i
                            class="mr-1 fa fa-trash"></i>@lang('site.save')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal show Users whose plans are Incomplete -->

    <div dir="rtl" class="modal fade" id="UsersPlansIncompleteModal" data-keyboard="false" data-backdrop="static"
        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                {{-- <div class="modal-header bg-light">
                    <h5>@lang('site.usersPlansIncomplete')</h5>
                </div> --}}

                <div class="modal-body" id="capture">
                    <div class="table-responsive">
                        <table id="example2" class="table text-center table-bordered table-hover dtr-inline sortable"
                            aria-describedby="example2_info">
                            <thead class="bg-light ">
                                <tr>
                                    <th class="no-sort" colspan="3">
                                        <h5>@lang('site.usersPlansIncomplete')</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>@lang('site.name')</th>
                                    <th>@lang('site.plansCount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $index =0;
                                @endphp
                                @forelse ($usersPlansIncomplete as $user)
                                <tr>
                                    @if ($user->events->count() < $workDaysOfTheWeek ) <td class="align-middle">{{
                                        $index += 1 }}</td>
                                        <td class="align-middle">{{ $user->name }}</span></td>
                                        <td class="align-middle"><span style="color:red">( {{ $user->events->count() }}
                                                )</span></td>
                                        @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">@lang('site.noReviews')</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-success" onclick="takeScreenshot()"><i class="fa fa-camera"></i> @lang('site.Screenshot')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.close')</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal show Schools that doesn't have users to visit -->

    <div dir="rtl" class="modal fade" id="SchoolsWithNoVisitsModal" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.schoolsWithNoVisits') ( @lang('site.total') {{ count($schoolsWithNoVisits) }} )</h5>
                </div>

                <div class="modal-body">

                    <!-- Modal Office -->
                    @if(auth()->user()->office->office_type == 0)
                    <div class="form-group mb-3" wire:ignore.self>
                        <label dir="rtl" for="office_id" class="col-form-label">@lang('site.offices') :</label>
                        <select wire:model.defer="data.office_id" id="office_id"
                            wire:change="OfficeOption($event.target.value)"
                            class="form-control fw-bold @error('office_id') is-invalid @enderror">
                            <option value="" hidden selected>@lang('site.choise', ['name' => 'مكتب التعليم /
                                إدارة']) :</option>
                            @foreach ($offices->where('office_type' , 1) as $office)
                            <option class="fw-bold {{ $loop->last ? 'bg-body-tertiary text-primary' : '' }}"
                                value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>

                        @error('office_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table id="example2" class="table text-center table-bordered table-hover dtr-inline sortable"
                            aria-describedby="example2_info">
                            <thead class="bg-light ">
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>@lang('site.schoolName')</th>
                                    <th>@lang('site.level')</th>
                                    <th>@lang('site.totalVisits')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schoolsWithNoVisits as $school)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $school->name }}</span></td>
                                    <td class="align-middle">{{ $school->level->name }}</td>
                                    <td class="align-middle">( {{ $school->events->count() }} )</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">@lang('site.noReviews')</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.close')</button>
                </div>
            </div>
        </div>
    </div>

    @section('script')

    {{-- <script src="{{ asset('backend/js/jquery.printPage.js') }}" type="text/javascript"></script> --}}

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

    <script>
        $(document).ready(function() {

                window.addEventListener('hide-form', function (event) {
                    $('#form').modal('hide');
                });

                window.addEventListener('show-form', function (event) {

                    $('#form').modal('show');

                    Livewire.hook('message.processed', (message, component) => {

                        $('.select2bs4').select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#form')
                        });

                        $('.select2bs4').on("select2:select", function (e) {
                            var selectedValue = $(e.currentTarget).val();
                            @this.set('data.task_id', selectedValue)
                        });

                    })

                });

                window.addEventListener('hide-modal-show', function (event) {
                    $('#modal-show-event').modal('hide');
                });

                window.addEventListener('show-modal-show', function (event) {
                    $('#modal-show-event').modal('show');
                });

                window.addEventListener('show-delete-modal', function (event) {
                    $('#confirmationModal').modal('show');
                });

                window.addEventListener('hide-delete-modal', function (event) {
                    $('#confirmationModal').modal('hide');
                });

                window.addEventListener('show-event-overlap-modal', function (event) {
                    $('#confirmationEventOverLapModal').modal('show');
                });

                window.addEventListener('hide-event-overlap-modal', function (event) {
                    $('#confirmationEventOverLapModal').modal('hide');
                });

                window.addEventListener('show-users-plans-incomplete-modal', function (event) {
                    $('#UsersPlansIncompleteModal').modal('show');
                });

                window.addEventListener('show-schools-with-no-visits-modal', function (event) {
                    $('#SchoolsWithNoVisitsModal').modal('show');
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

    <script>
        function takeScreenshot() {
            var element = document.getElementById('capture');
            html2canvas(element).then(function(canvas) {
                var imgData = canvas.toDataURL('image/jpeg');
                var a = document.createElement('a');
                a.href = imgData;
                a.download = 'downloaded_image.jpg';
                a.click();
            });
        }
    </script>

    @endsection
</div>
