<div>
    @push('style')
        <style>
            /*  */
        </style>
    @endpush
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@lang('site.dashboard')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('site.home')</a>
                        </li> --}}

                        {{-- Semester Filter --}}
                        <div class="d-inline pr-3">
                            <select dir="rtl" wire:model="bySemester" class="form-control form-control-sm mr-5">
                                <option value="" hidden selected>@lang('site.choise', ['name' => 'ألفصل الدراسي'])</option>
                                @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}" style="{{
                                    $semester->active ? 'color: blue; background:#F2F2F2;' : '' }}">{{ $semester->name
                                    }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Office Filter --}}
                        @role('superadmin')
                            <div class="d-inline pr-3">
                                <select dir="rtl" wire:model="byOffice" class="form-control form-control-sm mr-5">
                                    <option value="" hidden selected>@lang('site.choise', ['name' => 'مكتب التعليم'])</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endrole

                        <li class="breadcrumb-item active">@lang('site.dashboard')</li>
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
            <div class="row">
                <div class="col-lg-3 col-3">
                    <!-- Total Schools Count -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $schoolsCount }}</h3>

                            <p>@lang('site.schools')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-university"></i>
                        </div>
                        <a href="#" class="small-box-footer">@lang('site.moreInfo') <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-3">
                    <!-- Total Users Count -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $usersCount }}</h3>

                            <p>@lang('site.users')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">@lang('site.moreInfo') <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-3">
                    <!-- Total Events Count -->
                    <div class="small-box" style="background-color:rgba(38, 248, 255, 0.784);">
                        <div class="inner">
                            <div class="d-sm-inline-flex">
                                <h3>{{ $eventsCount }}</h3>
                            </div>

                            <p>@lang('site.events')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer text-dark">@lang('site.moreInfo')
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-3">
                    <!--  Total Weeks Count -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $weeksCount }}</h3>

                            <p>@lang('site.weeks')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-calendar"></i>
                        </div>
                        <a href="#" class="small-box-footer">@lang('site.moreInfo') <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-3">
                    <!-- Events Schools Count -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $eventsSchoolCount }}</h3>

                            <p>@lang('site.eventsSchool')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-model-s"></i>
                        </div>
                        <span class="small-box-footer"></span>
                        {{-- <a href="{{ route('admin.users') }}" class="small-box-footer">@lang('site.moreInfo') <i
                                class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-3">
                    <!-- Events Office Count -->
                    <div class="small-box text-white bg-secondary">
                        <div class="inner">
                            <h3>{{ $eventsOfficeCount }}</h3>

                            <p>@lang('site.eventsOffice')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-home"></i>
                        </div>
                        <span class="small-box-footer"></span>
                        {{-- <a href="{{ route('admin.users') }}" class="small-box-footer">@lang('site.moreInfo') <i
                                class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-3">
                    <!-- Events Training Count -->
                    <div class="small-box" style="background-color:rgb(239, 117, 47);">
                        <div class="inner">
                            <h3>{{ $eventsTrainingCount }}</h3>

                            <p>@lang('site.eventsTraining')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-stalker"></i>
                        </div>
                        <span class="small-box-footer"></span>
                        {{-- <a href="{{ route('admin.users') }}" class="small-box-footer">@lang('site.moreInfo') <i
                                class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-3">
                    <!-- Events Task Count -->
                    <div class="small-box" style="background-color:rgba(24, 37, 88, 0.342);">
                        <div class="inner">
                            <h3>{{ $eventsTaskCount }}</h3>

                            <p>@lang('site.eventsTask')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-plane"></i>
                        </div>
                        <span class="small-box-footer"></span>
                        {{-- <a href="{{ route('admin.users') }}" class="small-box-footer">@lang('site.moreInfo') <i
                                class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <section class="col-lg-12 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                @lang('site.barChart')
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="shadow rounded p-4 border text-center" style="height: 31rem;">
                                    {{-- Leevel tabs Filters --}}
                                    <div class="form-group clearfix">
                                        @foreach ($levels as $level)
                                            <div class="icheck-primary d-inline">
                                                <label for="radioPrimary{{ $level->id }}">
                                                    {{ $level->name }}
                                                </label>
                                                <input type="radio" id="radioPrimary{{ $level->id }}" wire:model="byLevel" value="{{ $level->id }}">
                                            </div>
                                        @endforeach
                                    </div><hr>

                                    {{-- highchart --}}
                                    <div id="highchart"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </section>

                {{-- Empty Tasks Table --}}
                @php
                    if (auth()->user()->gender == 1) {
                        $office_gender = [1, 2, 3, 4, 5, 6];
                    } else {
                        $office_gender = [7, 8, 9, 10, 12];
                    }
                @endphp
                @if (in_array($byOffice, $office_gender))
                    <section class="col-lg-12 connectedSortable">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fa fa-table"></i>
                                    @lang('site.emptyTasks')
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="form-group d-flex justify-content-between align-items-center">

                                    <label class="flex-wrap"  style="width: 200px;">
                                        @lang('site.totalRecord', ['name' => 'المدارس']) : &nbsp{{ $empty_schools->total() }}
                                    </label>

                                    <div>
                                        <select dir="rtl" wire:model="emptySchoolsPaginateValue" class="form-control">
                                            <option value="50" selected>50</option>
                                            <option value="100" selected>100</option>
                                            <option value="200" selected>200</option>
                                            <option value="20000" selected>@lang('site.all')</option>
                                        </select>
                                    </div>

                                    {{-- search and Export PDF & EXCEL --}}
                                    <div class="input-group" style="width: 350px;">

                                        <div class="card-tools">
                                            <div class="btn-group pr-2">
                                                <div class="pl-5">
                                                    {{-- Export Excel --}}
                                                    <a href="#"
                                                        class="btn btn-outline-light hover-item"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="@lang('site.exportExcel')"
                                                        wire:click.prevent="emptySchoolsExportExcel">
                                                        <i class="fa fa-file-excel text-success"></i>
                                                    </a>
                                                    {{-- Export PDF --}}
                                                    <a href="#" class="btn btn-outline-light hover-item"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="@lang('site.exportPDF')"
                                                        wire:click.prevent="emptySchoolsExportPDF">
                                                        <i class="fa fa-file-pdf text-danger"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- search text box --}}
                                        <input type="search" wire:model="emptySchoolSearchString" class="form-control"
                                        placeholder="@lang('site.searchFor')" value="">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>

                                {{-- Details Tasks Table --}}
                                <div class="table-responsive" dir="rtl">
                                    <div class="shadow rounded p-4 border">
                                        <div class="table-responsive">
                                            <table id="example1"
                                                class="table text-center table-bordered table-hover dataTable dtr-inline display nowrap"
                                                aria-describedby="example1_info" style="width:100%">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th colspan="4"><h4>@lang('site.emptyTasks')</h4></th>
                                                    </tr>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>@lang('site.school')</th>
                                                        <th>@lang('site.level')</th>
                                                        <th>@lang('site.visitedCount')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($empty_schools as $school)
                                                    <tr>
                                                        <td class="bg-light">{{ $loop->iteration }}</td>
                                                        <td>{{ $school->name }}</td>
                                                        <td>{{ $school->level->name }}</td>
                                                        <td class="text-red">{{ $school->events_count }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">@lang('site.noDataFound')</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                    <td colspan="10">
                                                            {!! $empty_schools->appends(request()->all())->links() !!}
                                                    </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </section>
                @endif

                <!-- Table for users Events plan -->
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fa fa-table"></i>
                                @lang('site.statisticsUsersEvent')
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group d-flex justify-content-between align-items-center">

                                <label class="flex-wrap"  style="width: 200px;">
                                    @lang('site.totalRecord', ['name' => 'المستخدمين']) : &nbsp{{ $users->total() }}
                                </label>

                                {{-- Paginate Filter --}}
                                <div>
                                    <select dir="rtl" wire:model="paginateValue" class="form-control">
                                        <option value="20" selected>20</option>
                                        <option value="50" selected>50</option>
                                        <option value="100" selected>100</option>
                                        <option value="10000" selected>@lang('site.all')</option>
                                    </select>
                                </div>

                                {{-- search and Export PDF & EXCEL --}}
                                <div class="input-group" style="width: 350px;">

                                    <div class="card-tools">
                                        <div class="btn-group pr-2">
                                            <div class="pl-5">
                                                {{-- Export EXCEL --}}
                                                <a href="#"
                                                    class="btn btn-outline-light hover-item"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="@lang('site.exportExcel')"
                                                    wire:click.prevent="UsersPlansExportExcel">
                                                    <i class="fa fa-file-excel text-success"></i>
                                                </a>
                                                {{-- Export PDF --}}
                                                <a href="#" class="btn btn-outline-light hover-item"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="@lang('site.exportPDF')"
                                                    wire:click.prevent="UsersPlansExportPDF">
                                                    <i class="fa fa-file-pdf text-danger"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- search text box --}}
                                    <input type="search" wire:model="searchTerm" class="form-control"
                                        placeholder="@lang('site.searchFor')" value="">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>

                            {{-- Details statistics Users Event Table --}}
                            <div class="table-responsive" dir="rtl">
                                <div class="shadow rounded p-4 border">
                                    <div class="table-responsive">
                                        <table id="example2"
                                            class="table text-center table-bordered table-hover dataTable dtr-inline display nowrap"
                                            aria-describedby="example2_info" style="width:100%">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th colspan="10"><h4>@lang('site.statisticsUsersEvent')</h4></th>
                                                </tr>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('site.name')</th>
                                                    <th>@lang('site.specialization')</th>
                                                    <th>@lang('site.type')</th>
                                                    <th>@lang('site.eventsSchool')</th>
                                                    <th>@lang('site.eventsOffice')</th>
                                                    <th>@lang('site.eventsTraining')</th>
                                                    <th>@lang('site.eventsTask')</th>
                                                    <th>@lang('site.eventsTotal')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($users as $user)
                                                <tr>
                                                    <td class="bg-light">{{ $loop->iteration }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->specialization->name }}</td>
                                                    <td>{{ $user->job_type->name }}</td>
                                                    <td>{{ $user->events->whereNotIn('task.name',['يوم مكتبي','برنامج تدريبي','إجازة','مكلف بمهمة'])->count() }}</td>
                                                    <td>{{ $user->events->where('task.name','يوم مكتبي')->count() }}</td>
                                                    <td>{{ $user->events->where('task.name','برنامج تدريبي')->count() }}</td>
                                                    <td>{{ $user->events->where('task.name','مكلف بمهمة')->count() }}</td>
                                                    <td class="bg-light">{{ $user->events->count() }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">@lang('site.noDataFound')</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                  <td colspan="10">
                                                        {!! $users->appends(request()->all())->links() !!}
                                                  </td>
                                                </tr>
                                              </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </section>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    @section('script')

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>

        <script>
            $(document).ready(function(){
                // for chartData
                const chart_data = @js($chartData);

                const [names, counts, need_cares] = chart_data;

                const colors = []; // Array to storage schools color if the school need care

                for (const [key, value] of Object.entries(need_cares)) {
                    colors.push(value ? '#ffbe89' : '#3ab7ff');
                }

                const chart = Highcharts.chart('highchart', {
                    chart: {
                        type: 'column',
                        renderTo: 'highchart',
                    },
                    title: {
                        text: 'المهام المنفذة خلال الفصل الدراسي',
                        format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                        useHTML: true,
                    },
                    // subtitle: {
                    //     text: 'Source: WorldClimate.com'
                    // },
                    xAxis: {
                        categories:  names,
                        crosshair: true,
                        useHTML: true,
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'عدد المهام'
                        },
                        format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                        useHTML: true,
                    },
                    tooltip: {
                        backgroundColor: '#FCFFC5',
                        borderWidth: 2,
                        // borderColor: '#AAA',
                        headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="padding:0">{series.name}: </td>' +
                            '<td style="color:green;"><b>{point.y: 1f}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                        useHTML: true,
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                        useHTML: true,
                    },
                    colors: colors,
                    series: [{
                        name: 'التكرار ',
                        data: counts,
                        cursor: 'pointer',
                        events: {
                            click: function () {
                                window.open('http://myplan.test/');
                            }
                        },
                        colorByPoint: true,
                        format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                        useHTML: true,
                    }],
                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            chartOptions: {
                                legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                }
                            }
                        }]
                    },
                    navigation: {
                        buttonOptions: {
                            align: 'right'
                        }
                    }
                });

                // Listener for refresh Calendar
                document.addEventListener('refreshEventChart', function({detail}) {
                    if (detail.refresh) {

                        const chart_data2 = JSON.parse(detail.data);

                        const [names, counts, need_cares] = chart_data2;

                        const colors = [];

                        for (const [key, value] of Object.entries(need_cares)) {
                            colors.push(value ? '#ffbe89' : '#3ab7ff');
                        }

                        const chart = Highcharts.chart('highchart', {
                            chart: {
                                type: 'column',
                                renderTo: 'highchart',
                            },
                            title: {
                                text: 'المهام المنفذة خلال الفصل الدراسي',
                                format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                                useHTML: true,
                            },
                            // subtitle: {
                            //     text: 'Source: WorldClimate.com'
                            // },
                            xAxis: {
                                categories:  names,
                                crosshair: true,
                                useHTML: true,
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'عدد المهام'
                                },
                                format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                                useHTML: true,
                            },
                            tooltip: {
                                backgroundColor: '#FCFFC5',
                                borderWidth: 2,
                                // borderColor: '#AAA',
                                headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="padding:0">{series.name}: </td>' +
                                    '<td style="color:green;"><b>{point.y: 1f}</b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                                useHTML: true,
                            },
                            plotOptions: {
                                column: {
                                    pointPadding: 0.2,
                                    borderWidth: 0
                                },
                                format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                                useHTML: true,
                            },
                            colors: colors,
                            series: [{
                                name: 'التكرار ',
                                data: counts,
                                cursor: 'pointer',
                                events: {
                                    click: function () {
                                        window.open('http://myplan.test/');
                                    }
                                },
                                colorByPoint: true,
                                format: '\u202B' + '{point.name}', // \u202B is RLE char for RTL support
                                useHTML: true,
                            }],
                            responsive: {
                                rules: [{
                                    condition: {
                                        maxWidth: 500
                                    },
                                    chartOptions: {
                                        legend: {
                                            layout: 'horizontal',
                                            align: 'center',
                                            verticalAlign: 'bottom'
                                        }
                                    }
                                }]
                            },
                            navigation: {
                                buttonOptions: {
                                    align: 'right'
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
</div>
