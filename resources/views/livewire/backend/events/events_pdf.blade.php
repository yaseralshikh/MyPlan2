<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطة المشرفين الأسبوعية</title>

    <style>
        /* Style the body */
        body {
            font-family: 'amiri';
            /* font-weight: bold; */
            display: table;
        }

        .container {
            margin: 10px;
            padding: 10px;
            width: 210mm;
            height: 297mm;
            /*border: solid green;*/
            text-align: center;
        }

        /* Header/logo Title */
        .header {
            margin-top: auto;
            margin-bottom: auto;
            margin-left: auto;
            margin-right: auto;
            /*border:solid red;*/
            /*background-color: green;*/
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo_header {
            border: none;
        }

        .content {
            padding: 35mm 15px 0 15px;
            height: 180mm;
            /*border:solid red;*/
            /* background-color: #fdfadb; */
        }

        h3 {
            padding: 2;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        td,
        th {
            border: 1px solid;
            text-align: center;
            font-size: inherit;
        }

        th {
            background-color: #ebebeb;
            padding: 5px;
        }

        .notes {
            padding: 5px;
            height: 25mm;
            /*border:solid red;*/
            /* background-color: rgb(130, 128, 246); */
        }

        /* Footer */
        footer {
            padding: 5px;
            height: 2mm;
            /*border:solid red;*/
            /* background-color: rgb(71, 156, 89); */
        }

        @page {
            margin-header: 5mm;
            margin-footer: 5mm;
            header: page-header;
            footer: page-footer;
        }
    </style>
</head>

<body>
    <div class="container">
        @foreach ($users as $user)
            @if ($user->events->count() <> 0)
                <htmlpageheader name="page-header">
                    <table class="logo_header" cellpadding="5" border="0" cellspacing="5">
                        <tbody class="logo_header">
                            <tr class="logo_header">
                                <td style="font-size: 16px;" class="logo_header">
                                    <div>
                                        <img style="display: block;" src="{{ asset('backend/img/events/moe_logo_r.jpg') }}"
                                            width="150px" alt="">
                                        <div>
                                            {{ $users[0]->office->name }}
                                        </div>
                                    </div>

                                </td>
                                <td class="logo_header">
                                    <img src="{{ asset('backend/img/events/moe_logo.jpg') }}" width="160px" alt="">
                                </td>
                                <td class="logo_header">
                                    <img src="{{ asset('backend/img/events/moe_logo_l.jpg') }}" width="150px" style="padding-bottom: 0.3cm;" alt="">
                                    <span style="font-size: 11px">{{ $users[0]->events[0]->week->name }} {{ $users[0]->events[0]->week->semester->school_year }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                </htmlpageheader>

                <div class="content">
                    <div style="padding-bottom: 0.30cm">
                        <h3 style="font-size: 30px;">تكليف مشرف تربوي</h3>
                        <h3>المكرم المشرف التربوي : <span style="font-size: 22px;background-color:#f4f4f4;">&nbsp;{{ $user->name
                                }}&nbsp;</span> &nbsp;&nbsp;&nbsp;وفقه الله</h3>
                        <h3>السلام عليكم ورحمة الله وبركاته</h3>
                        <h3>اعتمدوا القيام بالزيارات والمهام المحددة أدناه، سائلين الله لكم التوفيق</h3>
                    </div>

                    <table>
                        <thead>
                            {{-- <tr>
                                <th colspan="4">تفاصيل الزيارة</th>
                            </tr> --}}
                            <tr>
                                <th>#</th>
                                {{-- <th>الاسبوع</th> --}}
                                <th>أليوم</th>
                                <th>التاريخ</th>
                                <th>المهمة / المدرسة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->events as $index => $event)
                            <tr>
                                <td>{{ $index +1 }}</td>
                                {{-- <td>{{ $event->week->title }}</td> --}}
                                <td>{{ Alkoumi\LaravelHijriDate\Hijri::Date('l', $event->start) }}</td>
                                <td>{{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d', $event->start) }}</td>
                                <td>{{ $event->task->name }} {{ $event->task->name == 'مكلف بمهمة' || $event->task->name == 'برنامج تدريبي' ? ' : ( ' . $event->note . ' )' : '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        <ol style="text-align: justify;font-size: 13px;">
                            <span style="text-align: justify;font-size: 13px;font-weight: bold;">المهام :</span>

                            @foreach ($subtasks->where('section', 'مهمة فرعية') as $subtask)
                                <li>{{ $subtask->name }}</li>
                            @endforeach

                            @if ($subtasks[0]->section_type_id == 1)
                                @if ($office->assistant_signature_path)
                                    <img src="{{ $office->assistant_url }}" style="" width="75px" alt="">
                                @endif
                            @elseif ($subtasks[0]->section_type_id == 2)
                                @if ($office->assistant2_signature_path)
                                    <img src="{{ $office->assistant2_url }}" style="" width="75px" alt="">
                                @endif
                            @elseif ($subtasks[0]->section_type_id == 3)
                                @if ($office->assistant3_signature_path)
                                    <img src="{{ $office->assistant3_url }}" style="" width="75px" alt="">
                                @endif
                            @endif
                        </ol>
                    </div>
                </div>

                <div class="notes">
                    <table class="logo_header" cellpadding="5" cellspacing="5">
                        <tbody>
                            <tr>
                                <td dir="rtl" class="logo_header" style="text-align:justify;font-size: 11px;line-height: 1.5;">
                                    <ul style="list-style-type:none;">
                                        @foreach ($subtasks->where('section', 'حاشية') as $subtask)
                                        <li>{!! $subtask->name !!}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="logo_header" style="width: 45%;">
                                    <h3>مدير {{ $office->name }}</h3>

                                    @if ($office->director_signature_path)
                                        <img src="{{ $office->director_url }}"  style="float: center; margin: -0.4cm 0 -0.4cm 0" width="160px" alt="">
                                    @else
                                        <br>
                                    @endif

                                    <h3>{{ $office->director }}</h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <htmlpagefooter name="page-footer">
                    <table style="border-collapse: collapse;">
                        <tbody>
                            <tr style="border-top: 1px solid rgb(100, 100, 100);">
                                <td class="logo_header" style="text-align:right;font-size: 12px; padding-right:10px">رؤيتنا : تعليم ريادي.</td>
                                <td class="logo_header" style="text-align:left;font-size: 12px; padding-left:10px">{{ $users[0]->office->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </htmlpagefooter>
            @endif
        @endforeach
    </div>
</body>

</html>
