@extends('layouts.app')

@section('content')

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

                        <livewire:frontend.calendar />
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
