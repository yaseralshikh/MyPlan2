<div>

    @push('style')
        <style>
            .fc .fc-toolbar {
                display: flex;
                flex-wrap: wrap;
                /* justify-content: center; */
                font-size: 14px;
                border-radius: 5px;
                padding: 5px 15px 5px 15px;
                background-color: rgb(225, 245, 247);
            }

            .fc .fc-col-header {
                background-color: rgb(51, 81, 133);
            }

            .fc-col-header-cell-cushion {
                color: rgb(255, 254, 254);
            }

            .fc-h-event .fc-event-main-frame {
                display: block; /* for make fc-event-title-container expand */
                padding: 0 1px;
                white-space: normal;
            }

            .fc-daygrid-event {
                white-space: normal !important;
                align-items: normal !important;
            }
        </style>

    @endpush

    <div class="alert {{ auth()->user()->gender ? 'alert-success' : 'alert-danger' }} " dir="rtl" role="alert">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-dark" wire:click.prevent="editProfile({{ auth()->user()->id }})">@lang('site.profile')</button>
        </div>
        <h4 class="alert-heading">ملاحظة :</h4>
        <ul class="list-group list-group-flush">
            <li>الالتزام بإعداد الخطة الاسبوعية قبل نهاية دوام كل يوم ثلاثاء من كل اسبوع دراسي.</li>
            <li>مراعاة عدم حضور اكثر من مشرف تربوي في المدرسة الواحدة قدر الإمكان.</li>
            <li>الالتزام بالأيام المكتبية المتفق عليها حسب تعليمات إدارة المكتب.</li>
            <li>التعديل عند اللزوم قبل اعتماد الخطط.</li>
        </ul>
        <hr>
        <p class="mb-0">مع تحيات ادارة {{ auth()->user()->office->name }}.</p>
    </div>

    {{-- Calender --}}
    @if (auth()->user()->office->allowed_create_plans)
        <div id="calendar" wire:ignore></div>
    @else
        <div class="card mb-3 text-center">
            <img src="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" class="img-thumbnail border border-0 rounded mx-auto d-block mt-3 mb-3" alt="sorry">
            <div class="card-body mb-3" dir="rtl">
                <h2 class="card-text">المعذرة .. حسب توجيهات إدارة المكتب فقد تم إقفال إدخال الخطط من قبل المشرفين مؤقتاً وسيتم فتحها في وقت لاحق ، شكراُ على اهتمامكم .</h2>
            </div>
        </div>
    @endif

    <!-- Create Event - Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-body-tertiary">
                    <h1 class="modal-title fs-5" id="createModalLabel">@lang('site.addRecord', ['name' => 'خطة'])</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">

                        @if(!in_array(auth()->user()->office->id, [1,2,3,4,5,6,7,8,9,10,11,12]))
                            <!-- Modal offices -->
                            <div dir="rtl"  class="form-group mb-3" wire:ignore.self>
                                <label for="office_id" class="col-form-label">@lang('site.offices') :</label>
                                <select wire:model.defer="office_id" id="office_id" wire:change="OfficeOption($event.target.value)"
                                    class="form-control fw-bold @error('office_id') is-invalid @enderror" id="office_id">
                                    <option value="" hidden selected>@lang('site.choise', ['name' => 'مكتب التعليم']) :</option>
                                    @foreach ($offices as $office)
                                        <option class="fw-bold" value="{{ $office->id }}">{{ $office->name }}</option>
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
                        <div dir="rtl" class="form-group mb-3" wire:ignore.self>
                            <label for="level_id" class="col-form-label">@lang('site.level') :</label>
                            <select wire:model.defer="level_id" wire:change="LevelOption($event.target.value)" id="level_id"
                                class="form-control fw-bold @error('level_id') is-invalid @enderror">
                                <option value="" hidden selected>@lang('site.choise', ['name' => 'المرحلة']) :</option>
                                @foreach ($levels as $level)
                                    <option class="fw-bold" value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>

                            @error('level_id')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Modal Task (Event Title) -->
                        <div dir="rtl" class="form-group mb-3" wire:ignore.self>
                            <label for="task_id" class="col-form-label">@lang('site.task') :</label>
                            <select wire:model.defer="task_id" id="task_id"
                                class="form-control createSelect2bs4 @error('task_id') is-invalid @enderror" id="task_id">
                                <option value="" hidden selected >&nbsp;&nbsp; @lang('site.choise', ['name' => 'المهمة']) :</option>
                                @foreach ($tasks as $task)
                                    <option value="{{ $task->id }}">&nbsp;&nbsp; {{ $task->name }}</option>
                                @endforeach
                            </select>

                            @error('task_id')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- Modal Task ( Note ) -->
                        <div dir="rtl" class="form-group mb-3" wire:ignore.self>
                            <label for="note" class="col-form-label text-secondary">@lang('site.note') :</label>
                            <input type="text" wire:model.defer="note" class="form-control" id="note">

                            @error('note')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- start -->
                        <div class="mb-3">
                            <input type="hidden" wire:model.defer="start" class="form-control" id="start">
                        </div>

                        <!-- end -->
                        <div class="mb-3">
                            <input type="hidden" wire:model.defer="end" class="form-control" id="end">
                        </div>

                        {{-- Action --}}
                        <div class="d-flex justify-content-between align-items-center modal-footer bg-body-tertiary">
                            <div>
                                <button type="button" class="btn btn-secondary" wire:click="resetErrorMsg"
                                    data-bs-dismiss="modal">@lang('site.cancel')</button>
                                <button type="submit" class="btn btn-primary">@lang('site.save')</button>
                            </div>
                            @role('admin|superadmin')
                            <div class="form-check">
                                <input class="form-check-input" wire:model.defer="all_user" type="checkbox" value=""
                                    id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    @lang('site.eventForAllUsers')
                                </label>
                            </div>
                            @endrole
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
        <script src="{{ asset('js/locales-all.min.js') }}"></script>
        <script src="{{ asset('js/dayjs.min.js') }}"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function() {

                // Create Modal variables
                const createModalEl = document.getElementById('createModal');

                createModalEl.addEventListener('hidden.bs.modal', event => {

                    @this.office_id = '';
                    @this.level_id = '';
                    @this.task_id = '';
                    @this.note = '';
                    @this.start = '';
                    @this.end = '';

                });

                // Calendar variables and methods
                const calendarEl = document.getElementById('calendar');

                if (calendarEl != null) {

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        themeSystem: 'bootstrap5',
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            right: 'title',
                            left: 'dayGridMonth,listWeek,prev,next today'
                        },
                        timeZone: 'local',
                        locale: 'ar-sa',
                        displayEventTime : false,
                        hiddenDays: [ 5,6 ],
                        dayMaxEvents: 5, // allow "more" link when too many events
                        selectable: false,
                        droppable: true, // this allows things to be dropped onto the calendar
                        editable: true,
                        selectOverlap: false,
                        eventOverlap:false,

                        eventContent: function(info) {
                            console.log(info.event.extendedProps.task.name);
                            return {
                                html: '<h6 style="font-weight: bold; color:blue">&nbsp&nbsp<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp&nbsp'
                                    + info.event.extendedProps.task.name + '<span class="text-success">&nbsp&nbsp'
                                    + (info.event.extendedProps.status == 1 ? '<i class="fa fa-check" aria-hidden="true"></i>' : ''
                                    + '</span> </h6>')
                            }
                        },

                        dateClick: function(info) {
                            console.log([info]);
                            @this.start = info.dateStr;
                            @this.end = info.dateStr;
                            $('#createModal').modal('toggle');
                        },

                        // select: function(info) {
                        //     alert('selected ' + info.startStr + ' to ' + info.endStr);
                        //     console.log('selecting');
                        //     $('#createModal').modal('toggle');
                        // },

                    });

                    // for fill calendar
                    calendar.addEventSource({
                        url: '/api/calendar/events'
                    });

                    calendar.render();

                    // Listener close Modal Create
                    document.addEventListener('closeModalCreate', function({detail}) {
                        if (detail.close) {
                            $('#createModal').modal('toggle');
                        }
                    });

                    // Listener for refresh Calendar
                    document.addEventListener('refreshEventCalendar', function({detail}) {
                        if (detail.refresh) {
                            calendar.refetchEvents();
                        }
                    });

                    // when the Localization Aria selected option changes, dynamically change the calendar option
                    var localeSelectorEl = document.getElementById('locale-selector');
                    localeSelectorEl.addEventListener('change', function() {
                        if (this.value) {
                            calendar.setOption('locale', this.value);
                        }
                    });

                    Livewire.hook('message.processed', (message, component) => {

                        $('.createSelect2bs4').select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#createModal'),
                        });


                        $('.createSelect2bs4').on("select2:select", function (e) {
                            var selectedValue = $(e.currentTarget).val();
                            @this.task_id = selectedValue;
                        });

                        // $('.editSelect2bs4').select2({
                        //     theme: 'bootstrap4',
                        //     dropdownParent: $('#editModal')
                        // });

                        // $('.editSelect2bs4').on("select2:select", function (e) {
                        //     var selectedValue = $(e.currentTarget).val();
                        //     @this.task_id = selectedValue;
                        // });

                    });

                };

                // Listener for SweetAleart
                window.addEventListener('swal',function(e){
                    Swal.fire(e.detail);
                });

            });

        </script>

    @endpush

</div>
