<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="index3.html" class="brand-link">
        <img src="{{ asset('backend/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="pb-3 mt-3 mb-3 user-panel d-flex">
            <div class="image">
                <img src="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admin.dashboard') }}" class="d-block">{{ Str::upper(Auth::user()->name) }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            @lang('site.dashboard')
                        </p>
                    </a>
                </li>

                {{-- Users Section --}}
                <li class="nav-header text-secondary">
                    <h6>@lang('site.usersSection')</h6>
                </li>
                {{-- Users --}}
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}"
                        class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            @lang('site.users')
                        </p>
                    </a>
                </li>

                @role('operationsmanager')
                {{-- specializations --}}
                <li class="nav-item">
                    <a href="{{ route('admin.specializations') }}"
                        class="nav-link {{ request()->is('admin/specializations') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>
                            @lang('site.specializations')
                        </p>
                    </a>
                </li>
                {{-- Job Types --}}
                <li class="nav-item">
                    <a href="{{ route('admin.job_types') }}"
                        class="nav-link {{ request()->is('admin/job_types') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>
                            @lang('site.job_types')
                        </p>
                    </a>
                </li>
                {{-- Section Types --}}
                <li class="nav-item">
                    <a href="{{ route('admin.section_types') }}"
                        class="nav-link {{ request()->is('admin/section_types') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>
                            @lang('site.section_types')
                        </p>
                    </a>
                </li>
                {{-- Roles And Permissions  --}}
                <li class="nav-item">
                    <a href="{{ url('/laratrust') }}"
                        class="nav-link {{ request()->is('/laratrust') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <p>
                            @lang('site.rolesPermissions')
                        </p>
                    </a>
                </li>
                @endrole

                {{-- Events Section --}}
                <li class="nav-header text-secondary" style="border-top: 1px solid #4f5962;">
                    <h6>@lang('site.eventsSection')</h6>
                </li>
                {{-- Events --}}
                <li class="nav-item">
                    <a href="{{ route('admin.events') }}"
                        class="nav-link {{ request()->is('admin/events') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            @lang('site.events')
                        </p>
                    </a>
                </li>

                <li class="nav-header text-secondary" style="border-top: 1px solid #4f5962;">
                    <h6>@lang('site.tasksSection')</h6>
                </li>
                @if (auth()->user()->office->office_type == 1)
                    <li class="nav-item">
                        <a href="{{ route('admin.tasks') }}"
                            class="nav-link {{ request()->is('admin/tasks') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                @lang('site.tasks')
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('admin.subtasks') }}"
                        class="nav-link {{ request()->is('admin/subtasks') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            @lang('site.subTasks')
                        </p>
                    </a>
                </li>
                @role('operationsmanager')
                <li class="nav-item">
                    <a href="{{ route('admin.levels') }}"
                        class="nav-link {{ request()->is('admin/levels') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            @lang('site.levels')
                        </p>
                    </a>
                </li>

                {{-- Education Section --}}

                <li class="nav-header text-secondary" style="border-top: 1px solid #4f5962;">
                    <h6>@lang('site.educationSection')</h6>
                </li>

                {{-- Education --}}
                <li class="nav-item">
                    <a href="{{ route('admin.education') }}"
                        class="nav-link {{ request()->is('admin/education') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            @lang('site.educations')
                        </p>
                    </a>
                </li>

                {{-- Offices --}}
                <li class="nav-item">
                    <a href="{{ route('admin.offices') }}"
                        class="nav-link {{ request()->is('admin/offices') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            @lang('site.offices')
                        </p>
                    </a>
                </li>

                @endrole

                @role('operationsmanager|superadmin')
                    {{-- semester & week Section --}}
                    <li class="nav-header text-secondary" style="border-top: 1px solid #4f5962;">
                        <h6>@lang('site.semestersSection')</h6>
                    </li>
                    @role('operationsmanager')
                    <li class="nav-item">
                        <a href="{{ route('admin.semesters') }}"
                            class="nav-link {{ request()->is('admin/semesters') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                @lang('site.semesters')
                            </p>
                        </a>
                    </li>
                    @endrole

                    <li class="nav-item">
                        <a href="{{ route('admin.weeks') }}"
                            class="nav-link {{ request()->is('admin/weeks') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                @lang('site.weeks')
                            </p>
                        </a>
                    </li>
                @endrole

                @role('operationsmanager')
                    {{-- Settings Section --}}
                    <li class="nav-header text-secondary" style="border-top: 1px solid #4f5962;">
                        <h6>@lang('site.settingsSection')</h6>
                    </li>

                    {{-- log-viewer --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.log-viewer') }}"
                            target="_blank"
                            class="nav-link {{ request()->is('admin/log-viewer') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>
                                @lang('site.logViewer')
                            </p>
                        </a>
                    </li>
                @endrole

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
