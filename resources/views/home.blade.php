@extends('layouts.app')

@section('content')

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

    <div class="container-flud m-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>{{ __('اجندة المواعيد') }}</div>
                            {{-- Localization  Aria --}}
                            <div>
                                <select class="form-select form-select-sm" id='locale-selector' aria-label=".form-select-sm Locales">
                                    <option selected value="ar-sa">هجري</option>
                                    <option value="ar">ميلادي</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push("script")

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
        <script src="{{ asset('js/locales-all.min.js') }}"></script>
        <script src="{{ asset('js/dayjs.min.js') }}"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function() {

                const calendarEl = document.getElementById('calendar');

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
    
@endsection
