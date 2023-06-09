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
                                    class="form-control fw-bold @error('office_id') is-invalid @enderror">
                                    <option value="" hidden selected>@lang('site.choise', ['name' => 'مكتب التعليم / إدارة']) :</option>
                                    @foreach ($offices as $office)
                                        <option class="fw-bold {{ $loop->last ? 'bg-body-tertiary text-primary' : '' }}" value="{{ $office->id }}">{{ $office->name }}</option>
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
                                class="form-control createSelect2bs4 @error('task_id') is-invalid @enderror">
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
                        <div class="d-flex justify-content-between align-items-center modal-footer bg-body-tertiary border">
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

    <!-- Edit Event - Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-body-tertiary">
                    <h1 class="modal-title fs-5" id="editModalLabel">@lang('site.updateRecord', ['name' => 'خطة'])</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update">

                        @if(!in_array(auth()->user()->office->id, [1,2,3,4,5,6,7,8,9,10,11,12]))
                            <!-- Modal-Edit offices -->
                            <div dir="rtl"  class="form-group mb-3" wire:ignore.self>
                                <label for="office_id_edit" class="col-form-label">@lang('site.offices') :</label>
                                <select wire:model.defer="office_id" id="office_id_edit" wire:change="OfficeOption($event.target.value)"
                                    class="form-control fw-bold @error('office_id') is-invalid @enderror">
                                    <option value="" hidden selected>@lang('site.choise', ['name' => 'مكتب التعليم']) :</option>
                                    @foreach ($offices as $office)
                                        <option class="fw-bold {{ $loop->last ? 'bg-body-tertiary text-primary' : '' }}" value="{{ $office->id }}">{{ $office->name }}</option>
                                    @endforeach
                                </select>

                                @error('office_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endif

                        <!-- Modal-Edit Levels -->
                        <div dir="rtl" class="form-group mb-3" wire:ignore.self>
                            <label for="level_id_edit" class="col-form-label">@lang('site.level') :</label>
                            <select wire:model.defer="level_id" wire:change="LevelOption($event.target.value)" id="level_id_edit"
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

                        <!-- Modal-Edit Task (Event Title) -->
                        <div dir="rtl" class="form-group mb-3" wire:ignore.self>
                            <label for="task_id_edit" class="col-form-label">@lang('site.task') :</label>
                            <select wire:model.defer="task_id" id="task_id_edit"
                                class="form-control editSelect2bs4 @error('task_id') is-invalid @enderror">
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

                        <!-- Modal-Edit Task ( Note ) -->
                        <div dir="rtl" class="form-group mb-3" wire:ignore.self>
                            <label for="note_edit" class="col-form-label text-secondary">@lang('site.note') :</label>
                            <input type="text" wire:model.defer="note" class="form-control" id="_edit">

                            @error('note')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- Modal-Edit start -->
                        <div class="mb-3">
                            <input type="hidden" wire:model.defer="start" class="form-control" id="start_edit">
                        </div>

                        <!-- Modal-Edit end -->
                        <div class="mb-3">
                            <input type="hidden" wire:model.defer="end" class="form-control" id="end_edit">
                        </div>

                        {{-- Action --}}
                        <div class="d-flex justify-content-between align-items-center modal-footer bg-body-tertiary border">
                            <div>
                                <button type="button" class="btn btn-secondary" wire:click="resetErrorMsg"
                                    data-bs-dismiss="modal">@lang('site.cancel')</button>
                                <button type="submit" class="btn btn-primary">@lang('site.update')</button>
                            </div>
                            <button class="btn btn-danger" wire:click.prevent='delete'>@lang('site.delete')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Profile -->

    <div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editProfileModalLabel">
                        <span>@lang('site.updateRecord', ['name' => 'الملف الشخصي'])</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form autocomplete="off" wire:submit.prevent="updateProfile">
                    <div class="modal-body" dir="rtl">
                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">

                                <!-- Modal User Full Name -->

                                <div class="form-group">
                                    <label for="name">@lang('site.fullName') *</label>
                                    <input type="text" wire:model.defer="profileData.name"
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
                                    <label for="email">@lang('site.email') *</label>
                                    <input type="email" wire:model.defer="profileData.email"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        aria-describedby="emailHelp" placeholder="@lang('site.enterEmail')">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal User Specialization -->

                                <div class="form-group">
                                    <label for="specialization_id">@lang('site.specialization') *</label>
                                    <select id="specialization_id"
                                        class="form-control @error('specialization_id') is-invalid @enderror"
                                        wire:model.defer="profileData.specialization_id">
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

                                <!-- Modal User Password -->

                                <div class="form-group">
                                    <label for="password">@lang('site.password')</label>
                                    <input type="password" wire:model.defer="profileData.password"
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
                                    <label for="passwordConfirmation">@lang('site.passwordConfirmation')</label>
                                    <input type="password" wire:model.defer="profileData.password_confirmation"
                                        class="form-control" id="passwordConfirmation"
                                        placeholder="@lang('site.enterConfirmPassword')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="mr-1 fa fa-times"></i><span> @lang('site.cancel') </span>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mr-1 fa fa-save"></i><span> @lang('site.saveChanges') </span>
                        </button>
                    </div>
                </form>
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

                // Edit Modal variables
                const editModalEl = document.getElementById('editModal');

                editModalEl.addEventListener('hidden.bs.modal', event => {
                    @this.event_id = '';
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

                    const checkbox = document.getElementById('drop-remove');
                    const tooltip = null;
                    // User variables
                    const userID = {{ auth()->user()->id }};
                    const userOffice_id = {{ auth()->user()->office_id }};
                    const userRole = {{ auth()->user()->roles[0]->id }};

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

                        // Display event content
                        eventContent: function(info) {
                            return {
                                html: '<h6 style="font-weight: bold; color:blue">&nbsp&nbsp<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp&nbsp'
                                    + info.event.extendedProps.task.name + '<span class="text-success">&nbsp&nbsp'
                                    + (info.event.extendedProps.status == 1 ? '<i class="fa fa-check" aria-hidden="true"></i>' : '')
                                    + (info.event.extendedProps.task_done == 1 ? '<i class="fa fa-check" aria-hidden="true"></i>' : '')
                                    + '</span> </h6>'
                            }
                        },

                        // Add new event
                        dateClick: function(info) {
                            @this.start = info.dateStr;
                            @this.end = info.dateStr;

                            $('#createModal').modal('toggle');
                        },

                        // Add Multi Days
                        // select: function(info) {
                        //     alert('selected ' + info.startStr + ' to ' + info.endStr);
                        //     console.log('selecting');
                        //     $('#createModal').modal('toggle');
                        // },

                        // Edit event
                        eventClick: function({event}) {

                            if (userID == event.extendedProps.user_id || userRole != 3) {

                                if (event.extendedProps.status && userRole == 3) {

                                    Swal.fire({
                                        title: 'تم اعتماد المهمة ، لا يمكن التعديل الا بعد فك الاعتماد',
                                        timer: 2000,
                                        timerProgressBar: true,
                                        icon: 'error',
                                        toast: true,
                                        showConfirmButton: false,
                                        position: 'center'
                                    })

                                } else {

                                    @this.event_id      = event.id;
                                    @this.office_id     = event.extendedProps.task.office_id;
                                    @this.level_id      = event.extendedProps.task.level_id;
                                    @this.task_id       = event.extendedProps.task_id;
                                    @this.note          = event.extendedProps.note;
                                    @this.start         = dayjs(event.start).format('YYYY-MM-DD');
                                    @this.end           = dayjs(event.start).format('YYYY-MM-DD');

                                    $('#editModal').modal('toggle');

                                }

                            } else {

                                Swal.fire({
                                    title: 'لا تملك الصلاحية للتعديل !!',
                                    timer: 2000,
                                    icon: 'error',
                                    toast: true,
                                    showConfirmButton: false,
                                    position: 'center'
                                })

                            }

                        },

                        // event Drag-Drop
                        drop: function(event) {
                            // is the "remove after drop" checkbox checked?
                            if (checkbox.checked) {
                                // if so, remove the element from the "Draggable Events" list
                                event.draggedEl.parentNode.removeChild(event.draggedEl);
                            }
                        },

                        eventDrop: info => @this.eventDrop(info.event, info.oldEvent),
                        loading: function(isLoading) {
                            if (!isLoading) {
                                // Reset custom events
                                this.getEvents().forEach(function(e){
                                    if (e.source === null) { e.remove(); }
                                });
                            }
                        },

                        // event tooltip ( MouseEnter )
                        eventMouseEnter: function (info) {
                            $(info.el).tooltip({
                                title: info.event.extendedProps.week.name + ' ( '
                                        + info.event.extendedProps.semester.school_year  + ' ) ' + '<br />'
                                        + info.event.extendedProps.task.name + '<br />'+ '<span class="text-info">'
                                        + info.event.extendedProps.user.name + '</span>' + '<br />' + '<span class="text-warning">'
                                        + (info.event.extendedProps.status == 1 ? 'تم الاعتماد' : '' + '</span>')
                                        + (info.event.extendedProps.task_done == 1 ? ' وتم التنفيذ' : '' + '</span>'),
                                html: true,
                                content:'ssss',
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body'
                            });
                        },

                    });

                    // for fill calendar
                    calendar.addEventSource({
                        url: '/api/calendar/events'
                    });

                    // for render calendar
                    calendar.render();

                    // Listener close Modal Create event
                    document.addEventListener('closeModalCreate', function({detail}) {
                        if (detail.close) {
                            $('#createModal').modal('toggle');
                        }
                    });

                    // Listener close Modal Edit event
                    document.addEventListener('closeModalEdit', function({detail}) {
                        if (detail.close) {
                            $('#editModal').modal('toggle');
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

                        $('.editSelect2bs4').select2({
                            theme: 'bootstrap4',
                            dropdownParent: $('#editModal')
                        });

                        $('.editSelect2bs4').on("select2:select", function (e) {
                            var selectedValue = $(e.currentTarget).val();
                            @this.task_id = selectedValue;
                        });

                    });

                };

                // Listener for Profile
                window.addEventListener('hide-profile', function (event) {
                    $('#editProfile').modal('hide');
                });

                window.addEventListener('show-profile', function (event) {
                    $('#editProfile').modal('show');
                });

                // Listener for SweetAleart
                window.addEventListener('swal',function(e){
                    Swal.fire(e.detail);
                });

            });

        </script>

    @endpush

</div>
