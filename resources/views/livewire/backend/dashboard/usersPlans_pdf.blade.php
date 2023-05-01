<!DOCTYPE html>
<html lang="ar">
	<head>
		<meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>responsive HTML Users List</title>
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
        <htmlpageheader name="page-header">
            {{-- <img src="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" width="180px;" alt=""> --}}
        <h3>إحصائية خطط المشرفين خلال {{ $semester->name }} - {{ $users[0]->office->name }}</h3>
        </htmlpageheader>
		<div class="counter">
            <table class="">
                <thead class="">
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>ألتخصص</th>
                        <th>الادارة / مكتب التعليم</th>
                        <th>زيارات مدارس</th>
                        <th>ايام مكتبية</th>
                        <th>برامج تدريبية</th>
                        <th>مكلف بمهمة</th>
                        <th>مجموع الخطط</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->specialization->name }}</td>
                            <td>{{ $user->office->name }}</td>
                            <td>{{ $user->events->whereNotIn('task.name',['إجازة','برنامج تدريبي','يوم مكتبي','مكلف بمهمة'])->count() }}</td>
                            <td>{{ $user->events->where('task.name','يوم مكتبي' )->count() }}</td>
                            <td>{{ $user->events->where('task.name','برنامج تدريبي' )->count() }}</td>
                            <td>{{ $user->events->where('task.name','مكلف بمهمة' )->count() }}</td>
                            <td style="background-color: rgba(225, 222, 222, 0.455);">{{ $user->events->count() }}</td>
                        </tr>

                        <htmlpagefooter name="page-footer">
                            <table style="border-collapse: collapse;">
                                <tbody>
                                    <tr style="border-top: 1px solid rgb(100, 100, 100);">
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-left:10px">{{ date('Y-m-d') }}</td>
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-right:10px">رؤيتنا : تعليم ريادي.</td>
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-left:10px">{{ $users[0]->office->name }}</td>
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
