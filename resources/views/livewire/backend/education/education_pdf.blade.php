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
                width:10%;
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
            <h3>إدارات التعليم التي تعمل بنظام منصة خطتي</h3>
        </htmlpageheader>

        {{-- <img src="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" alt=""> --}}

		<div class="counter">
            <table class="">
                <thead class="">
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($educations as $index => $education)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $education->name }}</td>
                            <td>{{ $education->status() }}</td>
                        </tr>

                        <htmlpagefooter name="page-footer">
                            <table style="border-collapse: collapse;">
                                <tbody>
                                    <tr style="border-top: 1px solid rgb(100, 100, 100);">
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-left:10px">{{ date('Y-m-d') }}</td>
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-right:10px">رؤيتنا : تعليم ريادي</td>
                                        <td class="logo_header" style="text-align:center;font-size: 12px; padding-left:10px">منصة خطتي</td>
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
