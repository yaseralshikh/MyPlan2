<div>

    @push('style')
        <style>
            .fc .fc-toolbar {
                display: flex;
                flex-wrap: wrap;
                /* justify-content: center; */
                font-size: 14px;
                background-color: rgb(243, 243, 243);
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

    <div class="alert alert-success" dir="rtl" role="alert">
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

    <div id="calendar" wire:ignore></div>

    @push('script')

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
        <script src="{{ asset('js/locales-all.min.js') }}"></script>
        <script src="{{ asset('js/dayjs.min.js') }}"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function() {

                const calendarEl = document.getElementById('calendar');
                //console.log(calendarEl);
                if (calendarEl != null) {

                    const calendar = new FullCalendar.Calendar(calendarEl, {
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

                        eventContent: function(info) {
                            console.log(info.event.extendedProps.task.name);
                            return {
                                html: '<h6 style="font-weight: bold; color:blue">&nbsp&nbsp<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp&nbsp'
                                    + info.event.extendedProps.task.name + '<span class="text-success">&nbsp&nbsp'
                                    + (info.event.extendedProps.status == 1 ? '<i class="fa fa-check" aria-hidden="true"></i>' : ''
                                    + '</span> </h6>')
                            }
                        },
                    });

                    // for fill calendar
                    calendar.addEventSource({
                        url: '/api/calendar/events'
                    });

                    calendar.render();

                    // when the Localization Aria selected option changes, dynamically change the calendar option
                    var localeSelectorEl = document.getElementById('locale-selector');
                    localeSelectorEl.addEventListener('change', function() {
                        if (this.value) {
                            calendar.setOption('locale', this.value);
                        }
                    });

                }
            });

        </script>

    @endpush

</div>
