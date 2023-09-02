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
            <div class="card-header text-center">
                <img src="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" class="img-circle elevation-2">
                <a href="{{ route('home') }}" class="h1"><b>Admin</b>LTE</a>
                <h4 class="text-primary">(( خطتي ))</h4>
            </div>
            <div class="card-body">
                <p class="login-box-msg">@lang('site.registerNewMembership')</p>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    <!-- Name -->
                    <div class="input-group mb-3">
                        <input dir="rtl" type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="@lang('site.fullName')" name="name" value="{{ old('name') }}" required
                            autocomplete="name" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- E-Mail Address -->
                    <div class="input-group mb-3">
                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="@lang('site.email')" name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Mobile number -->
                    <div class="input-group mb-3">
                        <input type="number" id="mobile" class="form-control @error('mobile') is-invalid @enderror"
                            placeholder="@lang('site.mobile')" name="mobile" value="{{ old('mobile') }}" required
                            autocomplete="mobile" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-mobile"></span>
                            </div>
                        </div>
                        @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Gender -->
                    <div class="input-group mb-3">
                        <select name="gender" class="custom-select gender-education-select @error('gender') is-invalid @enderror"
                            id="inputGroupSelectgender">
                            <option disabled selected>@lang('site.gender')</option>
                            @foreach ($genders as $gender)
                            <option value="{{ $gender['id'] }}">{{ $gender['name'] }}</option>@json($gender['id'])
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="inputGroupSelectgender"><i class="fa fa-venus-mars"
                                    aria-hidden="true"></i></label>
                        </div>
                        @error('gender')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- education -->
                    <div class="input-group mb-3">
                        <select name="education_id" class="custom-select gender-education-select @error('education_id') is-invalid @enderror"
                            id="education">
                            <option disabled selected>@lang('site.education')</option>
                            @foreach ($educations as $education)
                                <option value="{{ $education->id }}">{{ $education->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="education"><i class="fa fa-briefcase"
                                    aria-hidden="true"></i></label>
                        </div>
                        @error('education_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Office -->
                    <div class="input-group mb-3">
                        <select name="office_id" class="custom-select @error('office_id') is-invalid @enderror"
                            id="office">
                            <option disabled selected>@lang('site.office')</option>
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="office"><i class="fa fa-briefcase"
                                    aria-hidden="true"></i></label>
                        </div>
                        @error('office_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Office -->
                    {{-- <div class="input-group mb-3">
                        <select name="office_id" class="custom-select @error('office_id') is-invalid @enderror"
                            id="inputGroupSelect02">
                            <option disabled selected>@lang('site.office')</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="inputGroupSelect02"><i class="fa fa-briefcase"
                                    aria-hidden="true"></i></label>
                        </div>
                        @error('office_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div> --}}
                    <!-- Specialization -->
                    <div class="input-group mb-3">
                        <select name="specialization_id"
                            class="custom-select @error('specialization_id') is-invalid @enderror"
                            id="inputGroupSelectSpecialization">
                            <option disabled selected>@lang('site.specialization')</option>
                            @foreach ($specializations as $specialization)
                            <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="inputGroupSelectSpecialization"><i class="fa fa-briefcase"
                                    aria-hidden="true"></i></label>
                        </div>
                        @error('specialization_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Job Type -->
                    <div class="input-group mb-3">
                        <select name="job_type_id" class="custom-select @error('job_type_id') is-invalid @enderror"
                            id="inputGroupSelectjob_type">
                            <option disabled selected>@lang('site.jobType')</option>
                            @foreach ($job_types as $job)
                            <option value="{{ $job->id }}">{{ $job->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="inputGroupSelectjob_type"><i class="fa fa-briefcase"
                                    aria-hidden="true"></i></label>
                        </div>
                        @error('job_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Section Type -->
                    <div class="input-group mb-3">
                        <select name="section_type_id" class="custom-select @error('section_type_id') is-invalid @enderror"
                            id="inputGroupSelectsection_type">
                            <option disabled selected>@lang('site.selectSectionType')</option>
                            @foreach ($section_types as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="inputGroupSelectsection_type"><i class="fa fa-briefcase"
                                    aria-hidden="true"></i></label>
                        </div>
                        @error('section_type_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            placeholder="@lang('site.password')" required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- Confirm Password -->
                    <div class="input-group mb-3">
                        <input id="password-confirm" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password_confirmation"
                            placeholder="@lang('site.passwordConfirmation')" required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    {{-- g-recaptcha-response --}}
                    <div class="input-group mb-3">
                        <input type="hidden" class="form-control @error('g-recaptcha-response') is-invalid @enderror" name="g-recaptcha-response" id="g-recaptcha-response" required>

                        @error('g-recaptcha-response')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- Register Button --}}
                    <div class="row">
                        <div class="col-8">
                            <a href="{{ route('login') }}" class="text-center">@lang('site.alreadyMembership')</a>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="button" onclick="onClick(event)" class="btn btn-primary btn-sm btn-block">{{ __('site.registerBtn')}}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->

    <!-- jQuery -->
    <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('backend/js/adminlte.min.js') }}"></script>
    {{-- RECAPTCHA V3 --}}
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
        function onClick(e) {
          e.preventDefault();
          grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'register'}).then(function(token) {
                document.getElementById("g-recaptcha-response").value = token;
                document.getElementById("registerForm").submit();
            });
          });
        }
    </script>

    {{-- Get Offices By education_id --}}
    <script>
        $(document).ready(function() {

            $('.gender-education-select').change(function() {
                var selectedGenderValue = $('#inputGroupSelectgender').val();
                var education_id = $('#education').val();

                if (education_id) {
                    $.ajax({
                        url: '/getOffices',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: '{{ csrf_token() }}',
                            education_id: education_id,
                            gender: selectedGenderValue
                        },
                        success: function(data) {
                            $('#office').empty();
                            $.each(data.offices, function(key, value) {
                                $('#office').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        },
                        error: function() {
                            alert('Failed to fetch data');
                        }
                    });
                } else {
                    $('#office').empty();
                }
            });

        });
    </script>
</body>

</html>
