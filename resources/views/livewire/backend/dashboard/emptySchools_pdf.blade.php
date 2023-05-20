<!DOCTYPE html>
<html lang="ar">
	<head>
		<meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>responsive HTML Tasks List</title>
		<!-- Invoice styling -->
		<style>
            *{
                direction: rtl;
                padding: 0%;
                margin: 0%;
            }
			body {
				font-family: 'amiri', sans-serif;
				text-align: center;
                display: table;
                direction: rtl;
			}
            table, td, th {
                border: 1px solid;
                text-align: center;
            }
            thead, tr ,th{
                padding: 10px;
            }
            .counter{
                padding: 10px;
                text-align: center;
            }
            th{
                background-color: rgb(225, 222, 222);
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            img{
                margin-bottom: 5px;
            }

            @page {

                margin-header: 5mm;
                margin-footer: 5mm;
                margin-top : 70px !important;
                margin-bottom : 50px !important;
                header: page-header;
                footer: page-footer;
            }
		</style>
	</head>

	<body>
        {{-- <img src="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" width="180px;" alt=""> --}}
        <htmlpageheader name="page-header">
		    <h3>كشف بالمدارس التي لم تزار خلال {{ $semester->name }} - {{ $tasks[0]->office->name }}</h3>
        </htmlpageheader>
		<div class="counter">
            <table class="">
                <thead class="">
                    <tr>
                        <th>#</th>
                        <th>اسم المدرسة</th>
                        <th>المرحلة</th>
                        <th>عدد الزيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $index => $task)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->level->name }}</td>
                            <td>{{ $task->events_count }}</td>
                        </tr>

                        <htmlpagefooter name="page-footer">
                            <table style="border-collapse: collapse;">
                                <tbody>
                                    <tr style="border-top: 1px solid rgb(100, 100, 100);">
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-left:10px">{{ date('Y-m-d') }} - {{ Alkoumi\LaravelHijriDate\Hijri::Date('Y-m-d') }}</td>
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-right:10px">رؤيتنا : تعليم ريادي.</td>
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-left:10px">{{ $tasks[0]->office->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </htmlpagefooter>
                    @endforeach
                </tbody>
            </table>
		</div>
	</body>
</html>
