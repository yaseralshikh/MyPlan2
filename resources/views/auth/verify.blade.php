<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Registration Page (v2)</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center" dir="rtl">
                <img src="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" class="img-circle elevation-2">
                <a href="{{ route('home') }}" class="h1"><b>Admin</b>LTE</a>
                <h4 class="text-primary">(( خطتي ))</h4>
                <hr>
                <h6>{{ __('التحقق من عنوان بريدك الإلكتروني.') }}</h6>
            </div>
            <div class="card-body text-justify" dir="rtl">
                @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني.') }}
                </div>
                @endif

                {{ __('قبل المتابعة ، يرجى التحقق من بريدك الإلكتروني للحصول على رابط التحقق.') }}
                {{ __('إذا لم تستلم البريد الإلكتروني') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('انقر هنا لطلب آخر')
                        }}</button>.
                </form>
                <!-- /.form-box -->
            </div><!-- /.card -->
            {{-- <div class="card-footer text-muted text-center">
                <a href="{{ route('login') }}" class="text-center">@lang('site.login')</a>
            </div> --}}
        </div>
        <!-- /.register-box -->

        <!-- jQuery -->
        <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('backend/js/adminlte.min.js') }}"></script>
</body>

</html>