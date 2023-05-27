<div>
    @section('style')
    <style>
        .disabled-link {
            cursor: default;
            pointer-events: none;
            text-decoration: none;
            color: rgb(174, 172, 172);
        }
    </style>
    @endsection

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">@lang('site.offices')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.offices')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <button wire:click.prevent='addNewOffice' class="ml-1 btn btn-sm btn-primary">
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'مكتب / ادارة'])</span>
                            </i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm">@lang('site.action')</button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item" wire:click.prevent="exportPDF"
                                    href="#">@lang('site.exportPDF')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.setActive')</a>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.setInActive')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item {{ $selectedRows ? 'text-danger' : 'disabled-link' }}  delete-confirm"
                                    wire:click.prevent="deleteSelectedRows" href="#">@lang('site.deleteSelected')</a>
                            </div>
                        </div>
                    </h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        {{-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group d-flex justify-content-between align-items-center">
                        {{-- search --}}
                        <div class="input-group" style="width: 200px;">
                            <input dir="rtl" type="search" wire:model="searchTerm" class="form-control"
                                placeholder="@lang('site.searchFor')..." value="">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- byGender Filter --}}
                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:model="byGender" class="custom-control-input"
                                id="customSwitchByGender">
                            <label dir="rtl" class="custom-control-label"
                                for="customSwitchByGender">@lang('site.gender') ( بنين ) </label>
                        </div>

                        {{-- byOfficeType Filter --}}
                        <div class="custom-control custom-switch">
                            <input type="checkbox" wire:model="byOfficeType" class="custom-control-input"
                                id="customSwitchByOfficeType">
                            <label class="custom-control-label"
                                for="customSwitchByOfficeType">@lang('site.offices_1')</label>
                        </div>

                        {{-- Education Filter --}}
                        <div>
                            <select dir="rtl" name="education_id" wire:model="byEducation"
                                class="form-control form-control-sm mr-5">
                                <option value="" selected>@lang('site.choise', [ 'name' => 'إدارة التعليم'])</option>
                                @foreach ($educations as $education)
                                <option value="{{ $education->id }}">
                                    {{ $education->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="flex-wrap">@lang('site.totalRecord', ['name' => 'المكاتب / الإدارات']) : &nbsp{{
                            $offices->total() }}</label>

                    </div>

                    @if ($selectedRows)
                    <span class="mb-2 text-success">
                        <i class="fa fa-level" aria-hidden="true"></i>
                        selected
                        <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                        Str::plural('office', count($selectedRows)) }}
                        <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                            data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                    </span>
                    @endif

                    <div class="table-responsive" data-aos="fade-up" wire:ignore.self>
                        <table id="example2" class="table text-center table-bordered table-hover dtr-inline sortable"
                            aria-describedby="example2_info">
                            <thead class="bg-light">
                                <tr>
                                    <td scope="col" class="no-sort">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </td>
                                    <th class="align-middle no-sort">#</th>
                                    <th class="align-middle">@lang('site.office')</th>
                                    <th class="align-middle">@lang('site.director')</th>
                                    <th class="align-middle no-sort">@lang('site.allowed_overlap')</th>
                                    <th class="align-middle no-sort">@lang('site.allowed_create_plans')</th>
                                    <th class="align-middle">@lang('site.education_id')</th>
                                    <th class="align-middle no-sort" scope="col">@lang('site.directorSignature')</th>
                                    <th class="align-middle no-sort" scope="col">@lang('site.assistantSignature')</th>
                                    <th class="align-middle no-sort" scope="col">@lang('site.assistant2Signature')</th>
                                    <th class="align-middle no-sort" scope="col">@lang('site.assistant3Signature')</th>
                                    <th class="align-middle">@lang('site.office_type')</th class="align-middle">
                                    <th class="align-middle">@lang('site.gender')</th class="align-middle">
                                    <th class="align-middle">@lang('site.status')</th class="align-middle">
                                    <th class="no-sort" colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($offices as $office)
                                <tr>
                                    <td scope="col" class="align-middle">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectedRows" value="{{ $office->id }}"
                                                class="custom-control-input" id="{{ $office->id }}">
                                            <label class="custom-control-label" for="{{ $office->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $office->name }}</td>
                                    <td class="align-middle">{{ $office->director }}</td>
                                    <td class="align-middle">
                                        <select class="form-control form-control-sm"
                                            wire:change='updateAllowedOverLapValue({{ $office }}, $event.target.value)'>
                                            <option value="0" {{ $office->allowed_overlap == 0 ? 'selected' : '' }}>0
                                            </option>
                                            <option value="1" {{ $office->allowed_overlap == 1 ? 'selected' : '' }}>1
                                            </option>
                                            <option value="2" {{ $office->allowed_overlap == 2 ? 'selected' : '' }}>2
                                            </option>
                                            <option value="3" {{ $office->allowed_overlap == 3 ? 'selected' : '' }}>3
                                            </option>
                                            <option value="4" {{ $office->allowed_overlap == 4 ? 'selected' : '' }}>4
                                            </option>
                                        </select>
                                    </td>
                                    <td class="align-middle">
                                        <select class="form-control form-control-sm"
                                            style="color:white ;background-color: {{ $office->allowed_create_plans ? '#28A745' : '#808080' }};"
                                            wire:change='updateAllowedCreatePlanStatus({{ $office }}, $event.target.value)'>
                                            <option value="0" {{ $office->allowed_create_plans ? 'selected' : ''
                                                }}>@lang('site.close')</option>
                                            <option value="1" {{ $office->allowed_create_plans ? 'selected' : ''
                                                }}>@lang('site.open')</option>
                                        </select>
                                    </td>
                                    <td class="align-middle">{{ $office->education->name }}</td>
                                    <td class="align-middle">
                                        <img src="{{ $office->director_url }}" style="width: 50px;" class="img" alt="">
                                        @if ($office->director_signature_path)
                                        <button wire:click.prevent="removeDirectorImage({{ $office->id }})"
                                            class="btn btn-outline-danger btn-xs">@lang('site.removeImage')</button>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <img src="{{ $office->assistant_url }}" style="width: 50px;" class="img" alt="">
                                        @if ($office->assistant_signature_path)
                                        <button wire:click.prevent="removeAssistantImage({{ $office->id }})"
                                            class="btn btn-outline-danger btn-xs">@lang('site.removeImage')</button>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <img src="{{ $office->assistant2_url }}" style="width: 50px;" class="img"
                                            alt="">
                                        @if ($office->assistant2_signature_path)
                                        <button wire:click.prevent="removeAssistant2Image({{ $office->id }})"
                                            class="btn btn-outline-danger btn-xs">@lang('site.removeImage')</button>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <img src="{{ $office->assistant3_url }}" style="width: 50px;" class="img"
                                            alt="">
                                        @if ($office->assistant2_signature_path)
                                        <button wire:click.prevent="removeAssistant3Image({{ $office->id }})"
                                            class="btn btn-outline-danger btn-xs">@lang('site.removeImage')</button>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="font-weight-bold badge text-white {{ $office->office_type == 1 ? 'bg-info' : 'bg-warning' }}">
                                            {{ $office->office_type() }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="font-weight-bold badge text-white"
                                            style="background-color: {{ $office->gender == 1 ? '#0080FF' : '#CC00CC' }}">
                                            {{ $office->gender() }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="font-weight-bold badge text-white {{ $office->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $office->status() }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group btn-group-sm">
                                            @if (auth()->user()->hasPermission('offices-update'))
                                            <button wire:click.prevent="edit({{ $office }})"
                                                class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                            @else
                                            <button class="btn btn-primary btn-sm" disabled><i
                                                    class="fa fa-edit"></i></button>
                                            @endif

                                            @if (auth()->user()->hasPermission('offices-delete'))
                                            <button wire:click.prevent="confirmOfficeRemoval({{ $office->id }})"
                                                class="btn btn-danger btn-sm"><i
                                                    class="fa fa-trash bg-danger"></i></button>
                                            @else
                                            <button class="btn btn-danger btn-sm" disabled><i
                                                    class="fa fa-trash bg-danger"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="15" class="text-center">@lang('site.noDataFound')</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $offices->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update office -->

    <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateOffice' : 'createOffice' }}"
                enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'مكتب / إدارة'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'مكتب / إدارة'])</span>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">

                                <!-- Modal Office ( Education ) -->
                                <div dir="rtl" class="form-group mb-3" wire:ignore.self>
                                    <label for="education_id" class="col-form-label">@lang('site.education') :</label>
                                    <select wire:model.defer="data.education_id" id="education_id"
                                        class="form-control fw-bold @error('education_id') is-invalid @enderror">
                                        <option value="" hidden selected>@lang('site.choise', ['name' => 'إدارة
                                            التعليم']) :</option>
                                        @foreach ($educations as $education)
                                        <option class="fw-bold" value="{{ $education->id }}">{{ $education->name }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('education_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <!-- Modal Office Name -->
                                <div class="form-group">
                                    <label for="title">@lang('site.office')</label>
                                    <input type="text" wire:model.defer="data.name"
                                        class="form-control @error('title') is-invalid @enderror" id="title"
                                        aria-describedby="titleHelp" dir="rtl"
                                        placeholder="@lang('site.enterFieldName', ['name' => 'اسم المكتب / الإدارة'])">
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal director -->
                                <div class="form-group">
                                    <label for="director">@lang('site.director')</label>
                                    <input type="text" wire:model.defer="data.director"
                                        class="form-control @error('director') is-invalid @enderror" id="director"
                                        aria-describedby="directorHelp" dir="rtl"
                                        placeholder="@lang('site.enterFieldName', ['name' => 'اسم المدير'])">
                                    @error('director')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Modal director signature -->
                                <div class="form-group">
                                    <label for="custom-file">@lang('site.directorSignature')</label>
                                    @if ($director_signature_image)
                                    <img src="{{ $director_signature_image->temporaryUrl() }}"
                                        class="mb-2 d-block img img-circle" width="100px" alt="">
                                    @else
                                    <img src="{{ $data['director_url'] ?? '' }}" class="mb-2 d-block img img-circle"
                                        width="100px" alt="">
                                    @endif
                                    <div class="mb-3 custom-file">
                                        <div x-data="{ isUploading: false, progress: 5 }"
                                            x-on:livewire-upload-start="isUploading = true"
                                            x-on:livewire-upload-finish="isUploading = false; progress = 5"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input wire:model="director_signature_image" type="file"
                                                class="custom-file-input @error('director_signature_image') is-invalid @enderror"
                                                id="validatedCustomFile">
                                            {{-- progres bar --}}
                                            <div x-show.transition="isUploading"
                                                class="mt-2 rounded progress progress-sm">
                                                <div class="progress-bar bg-primary progress-bar-striped"
                                                    role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                    aria-valuemax="100" x-bind:style="`width: ${progress}%`">
                                                    <span class="sr-only">40% Complete (success)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="custom-file-label" for="customFile">
                                            @if ($director_signature_image)
                                            {{ $director_signature_image->getClientOriginalName() }}
                                            <img src="{{ $director_signature_image }}"
                                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
                                                alt="">
                                            @else
                                            @lang('site.chooseImage')
                                            @endif
                                        </label>
                                    </div>
                                </div>


                                <!-- Modal assistant signature -->

                                <div class="form-group">
                                    <label for="custom-file">@lang('site.assistantSignature')</label>
                                    @if ($assistant_signature_image)
                                    <img src="{{ $assistant_signature_image->temporaryUrl() }}"
                                        class="mb-2 d-block img img-circle" width="100px" alt="">
                                    @else
                                    <img src="{{ $data['assistant_url'] ?? '' }}" class="mb-2 d-block img img-circle"
                                        width="100px" alt="">
                                    @endif
                                    <div class="mb-3 custom-file">
                                        <div x-data="{ isUploading: false, progress: 5 }"
                                            x-on:livewire-upload-start="isUploading = true"
                                            x-on:livewire-upload-finish="isUploading = false; progress = 5"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input wire:model="assistant_signature_image" type="file"
                                                class="custom-file-input @error('assistant_signature_image') is-invalid @enderror"
                                                id="validatedCustomFile">
                                            {{-- progres bar --}}
                                            <div x-show.transition="isUploading"
                                                class="mt-2 rounded progress progress-sm">
                                                <div class="progress-bar bg-primary progress-bar-striped"
                                                    role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                    aria-valuemax="100" x-bind:style="`width: ${progress}%`">
                                                    <span class="sr-only">40% Complete (success)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="custom-file-label" for="customFile">
                                            @if ($assistant_signature_image)
                                            {{ $assistant_signature_image->getClientOriginalName() }}
                                            <img src="{{ $assistant_signature_image }}"
                                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
                                                alt="">
                                            @else
                                            @lang('site.chooseImage')
                                            @endif
                                        </label>
                                    </div>
                                </div>

                                <!-- Modal assistant2 signature -->

                                <div class="form-group">
                                    <label for="custom-file">@lang('site.assistant2Signature')</label>
                                    @if ($assistant2_signature_image)
                                    <img src="{{ $assistant2_signature_image->temporaryUrl() }}"
                                        class="mb-2 d-block img img-circle" width="100px" alt="">
                                    @else
                                    <img src="{{ $data['assistant2_url'] ?? '' }}" class="mb-2 d-block img img-circle"
                                        width="100px" alt="">
                                    @endif
                                    <div class="mb-3 custom-file">
                                        <div x-data="{ isUploading: false, progress: 5 }"
                                            x-on:livewire-upload-start="isUploading = true"
                                            x-on:livewire-upload-finish="isUploading = false; progress = 5"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input wire:model="assistant2_signature_image" type="file"
                                                class="custom-file-input @error('assistant2_signature_image') is-invalid @enderror"
                                                id="validatedCustomFile">
                                            {{-- progres bar --}}
                                            <div x-show.transition="isUploading"
                                                class="mt-2 rounded progress progress-sm">
                                                <div class="progress-bar bg-primary progress-bar-striped"
                                                    role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                    aria-valuemax="100" x-bind:style="`width: ${progress}%`">
                                                    <span class="sr-only">40% Complete (success)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="custom-file-label" for="customFile">
                                            @if ($assistant2_signature_image)
                                            {{ $assistant2_signature_image->getClientOriginalName() }}
                                            <img src="{{ $assistant2_signature_image }}"
                                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
                                                alt="">
                                            @else
                                            @lang('site.chooseImage')
                                            @endif
                                        </label>
                                    </div>
                                </div>

                                <!-- Modal assistant3 signature -->

                                <div class="form-group">
                                    <label for="custom-file">@lang('site.assistant3Signature')</label>
                                    @if ($assistant3_signature_image)
                                    <img src="{{ $assistant3_signature_image->temporaryUrl() }}"
                                        class="mb-2 d-block img img-circle" width="100px" alt="">
                                    @else
                                    <img src="{{ $data['assistant3_url'] ?? '' }}" class="mb-2 d-block img img-circle"
                                        width="100px" alt="">
                                    @endif
                                    <div class="mb-3 custom-file">
                                        <div x-data="{ isUploading: false, progress: 5 }"
                                            x-on:livewire-upload-start="isUploading = true"
                                            x-on:livewire-upload-finish="isUploading = false; progress = 5"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input wire:model="assistant3_signature_image" type="file"
                                                class="custom-file-input @error('assistant3_signature_image') is-invalid @enderror"
                                                id="validatedCustomFile">
                                            {{-- progres bar --}}
                                            <div x-show.transition="isUploading"
                                                class="mt-2 rounded progress progress-sm">
                                                <div class="progress-bar bg-primary progress-bar-striped"
                                                    role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                    aria-valuemax="100" x-bind:style="`width: ${progress}%`">
                                                    <span class="sr-only">40% Complete (success)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="custom-file-label" for="customFile">
                                            @if ($assistant3_signature_image)
                                            {{ $assistant3_signature_image->getClientOriginalName() }}
                                            <img src="{{ $assistant3_signature_image }}"
                                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}"
                                                alt="">
                                            @else
                                            @lang('site.chooseImage')
                                            @endif
                                        </label>
                                    </div>
                                </div>

                                <!-- Modal Office office_type -->

                                <div class="form-group clearfix">
                                    <label for="office_typeRadio" class="d-inline">@lang('site.kind') &nbsp; &nbsp; :</label>
                                    <div class="icheck-primary d-inline ml-2 mr-2">
                                        <input type="radio" id="radioPrimaryOffice_type1" wire:model="data.office_type"
                                            value="1">
                                        <label for="radioPrimaryOffice_type1">@lang('site.officeType')</label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimaryOffice_type2" wire:model="data.office_type"
                                            value="0">
                                        <label for="radioPrimaryOffice_type2">@lang('site.managementType')</label>
                                    </div>
                                </div>

                                <!-- Modal Office Gender -->

                                <div class="form-group clearfix">
                                    <label for="genderRadio" class="d-inline">@lang('site.gender') &nbsp; :</label>
                                    <div class="icheck-primary d-inline ml-2 mr-2">
                                        <input type="radio" id="radioPrimaryGender1" wire:model="data.gender" value="1">
                                        <label for="radioPrimaryGender1">@lang('site.male')</label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimaryGender2" wire:model="data.gender" value="0">
                                        <label for="radioPrimaryGender2">@lang('site.female')</label>
                                    </div>
                                </div>

                                <!-- Modal Office Status -->

                                <div class="form-group clearfix">
                                    <label for="statusRadio" class="d-inline">@lang('site.status') &nbsp;&nbsp;&nbsp; :</label>
                                    <div class="icheck-primary d-inline ml-2 mr-2">
                                        <input type="radio" id="radioPrimaryStatus1" wire:model="data.status" value="1">
                                        <label for="radioPrimaryStatus1">@lang('site.active')</label>
                                    </div>
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimaryStatus2" wire:model="data.status" value="0">
                                        <label for="radioPrimaryStatus2">@lang('site.inActive')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                                class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                        <button type="submit" class="btn btn-primary"><i class="mr-1 fa fa-save"></i>
                            @if ($showEditModal)
                            <span>@lang('site.saveChanges')</span>
                            @else
                            <span>@lang('site.save')</span>
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Office -->

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'مكتب / إدارة'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteOffice" class="btn btn-danger"><i
                            class="mr-1 fa fa-trash"></i>@lang('site.delete')</button>
                </div>
            </div>
        </div>
    </div>

    @section('script')

    <script>
        $(document).ready( function() {
                window.addEventListener('hide-form', function (event) {
                    $('#form').modal('hide');
                });
                window.addEventListener('show-form', function (event) {
                    $('#form').modal('show');
                });
                window.addEventListener('show-delete-modal', function (event) {
                    $('#confirmationModal').modal('show');
                });
                window.addEventListener('hide-delete-modal', function (event) {
                    $('#confirmationModal').modal('hide');
                });
            });
    </script>

    {{-- show-delete-alert-confirmation --}}

    <script>
        window.addEventListener('show-delete-alert-confirmation', event =>{
                Swal.fire({
                    title: '?@lang("site.delete")',
                    text: "@lang('site.deleteMessage')",
                    icon: 'warning',
                    iconHtml: '؟',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'لا',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('deleteConfirmed')
                    }
                })
            })
    </script>

    @endsection

</div>
