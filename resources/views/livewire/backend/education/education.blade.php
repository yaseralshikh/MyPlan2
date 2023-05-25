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
                    <h1 class="m-0">@lang('site.educations')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">@lang('site.dashboard')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('site.educations')</li>
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
                        <button wire:click.prevent='addNewEducation' class="ml-1 btn btn-sm btn-primary">
                            <i class="mr-2 fa fa-plus-circle" aria-hidden="true">
                                <span>@lang('site.addRecord', ['name' => 'إدارة تعليم'])</span>
                            </i>
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm">@lang('site.action')</button>
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" dir="rtl">
                                <a class="dropdown-item" wire:click.prevent="exportPDF"
                                    href="#">@lang('site.exportPDF')</a>
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsActive" href="#">@lang('site.setActive')</a>
                                <a class="dropdown-item {{ $selectedRows ? '' : 'disabled-link' }}"
                                    wire:click.prevent="setAllAsInActive" href="#">@lang('site.setInActive')</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item {{ $selectedRows && auth()->user()->hasPermission('education-delete') ? 'text-danger' : 'disabled-link' }}  delete-confirm"
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
                            <input type="search" wire:model="searchTerm" class="form-control"
                                placeholder="@lang('site.searchFor')" value="">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="flex-wrap">
                                @lang('site.totalRecord', ['name' => 'إدارات التعليم']) : &nbsp {{ $educations->total() }}
                            </label>
                        </div>

                    </div>

                    @if ($selectedRows)
                        <span class="mb-2 text-success">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            selected
                            <span class="text-dark font-weight-bold">{{ count($selectedRows) }}</span> {{
                            Str::plural('education', count($selectedRows)) }}
                            <a class="ml-2 text-gray" href="" wire:click="resetSelectedRows" data-toggle="tooltip"
                                data-placement="top" title="Reset Selected Rows"><i class="fas fa-times"></i></a>
                        </span>
                    @endif

                    <div class="table-responsive">
                        <table id="example2" class="table text-center table-bordered table-hover dtr-inline sortable"
                            aria-describedby="example2_info">
                            <thead class="bg-light">
                                <tr>
                                    <td class="align-middle">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectPageRows" value=""
                                                class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck"></label>
                                        </div>
                                    </td>
                                    <th class="align-middle">#</th>
                                    <th class="align-middle">
                                        @lang('site.name')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.officeType')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.managementType')
                                    </th>
                                    <th class="align-middle">
                                        @lang('site.status')
                                    </th>
                                    <th class="align-middle no-sort" colspan="2">@lang('site.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($educations as $education)
                                <tr>
                                    <td scope="col">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" wire:model="selectedRows" value="{{ $education->id }}"
                                                class="custom-control-input" id="{{ $education->id }}">
                                            <label class="custom-control-label" for="{{ $education->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $education->name }}</td>
                                    <td>{{ $education->offices->where('office_type', 1)->count() }}</td>
                                    <td>{{ $education->offices->where('office_type', 0)->count() }}</td>
                                    <td>
                                        <span
                                            class="font-weight-bold badge text-white {{ $education->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $education->status() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if (auth()->user()->hasPermission('offices-update'))
                                                <button wire:click.prevent="edit({{ $education }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                            @else
                                                <button class="btn btn-primary btn-sm" disabled><i class="fa fa-edit"></i></button>
                                            @endif

                                            @if (auth()->user()->hasPermission('education-delete'))
                                                <button wire:click.prevent="confirmEducationRemoval({{ $education->id }})"
                                                    class="btn btn-danger btn-sm"><i
                                                        class="fa fa-trash bg-danger"></i></button>
                                            @else
                                                <button class="btn btn-danger btn-sm" disabled><i
                                                        class="fa fa-trash bg-danger"></i></button>
                                            @endif
                                        </div>
                                        {{-- <form action="" method="post" id="delete-user-{{ $user->id }}"
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form> --}}
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">@lang('site.noDataFound')</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    {!! $educations->appends(request()->all())->links() !!}
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Create or Update Education -->

    <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateEducation' : 'createEducation' }}">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @if ($showEditModal)
                            <span>@lang('site.updateRecord', ['name' => 'إدارة تعليم'])</span>
                            @else
                            <span>@lang('site.addRecord', ['name' => 'إدارة تعليم'])</span>
                            @endif
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row h-100 justify-content-center align-items-center">
                            <div class="col-12">

                                <!-- Modal Education Name -->

                                <div  class="form-group">
                                    <label for="name">@lang('site.educationName')</label>
                                    <input dir="rtl" type="text" wire:model.defer="data.name"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        aria-describedby="nameHelp" placeholder="@lang('site.educationName')">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <!-- Modal User Status -->
                        <div class="form-group clearfix">
                            <label for="statusRadio" class="d-inline">@lang('site.status') :</label>
                            <div class="icheck-primary d-inline ml-2 mr-2">
                                <input type="radio" id="radioPrimary1" wire:model="data.status" value="1">
                                <label for="radioPrimary1">@lang('site.active')</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioPrimary2" wire:model="data.status" value="0">
                                <label for="radioPrimary2">@lang('site.inActive')</label>
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

    <!-- Modal Delete Education -->

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5>@lang('site.deleteRecord', ['name' => 'إدارة تعليم'])</h5>
                </div>

                <div class="modal-body">
                    <h4>@lang('site.deleteMessage')</h4>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="mr-1 fa fa-times"></i> @lang('site.cancel')</button>
                    <button type="button" wire:click.prevent="deleteEducation" class="btn btn-danger"><i
                            class="mr-1 fa fa-trash"></i>@lang('site.delete')</button>
                </div>
            </div>
        </div>
    </div>

    @section('script')

        {{-- <script src="{{ asset('backend/js/jquery.printPage.js') }}" type="text/javascript"></script> --}}

        <script>
            $(document).ready( function() {

                    window.addEventListener('hide-form', function (event) {
                        $('#form').modal('hide');
                    });

                    window.addEventListener('show-form', function (event) {
                        $('#form').modal('show');
                    });

                    window.addEventListener('hide-modal-show', function (event) {
                        $('#modal-show-user').modal('hide');
                    });

                    window.addEventListener('show-modal-show', function (event) {
                        $('#modal-show-user').modal('show');
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


