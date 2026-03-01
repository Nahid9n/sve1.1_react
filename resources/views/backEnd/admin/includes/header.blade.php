<header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <a href="{{ route('home') }}" target="_blank" class="navbar-brand fs-4 text-info">Visit Website</a>
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    {{-- <span class="avatar avatar-sm" style="background-image: url({{ asset('backEnd/assets/images/default_avatar.jpg') }})"></span> --}}
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::user()->name }}</div>
                        {{-- <div class="mt-1 small text-muted text-capitalize">{{Auth::guard('admin')->user()->role}}</div> --}}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    {{-- <a href="{{route('admin.profile.edit')}}" class="dropdown-item"><i class="icon ti ti-edit"></i> &nbsp;Edit Profile</a> --}}
                    <a href="{{ Auth::guard('admin')->check() ? route('admin.change_pass') : (Auth::guard('manager')->check() ? route('manager.change_pass') : (Auth::guard('employee')->check() ? route('employee.change_pass') : '')) }}"
                        class="dropdown-item"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-restore">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3.06 13a9 9 0 1 0 .49 -4.087" />
                            <path d="M3 4.001v5h5" />
                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                        </svg> &nbsp;Change Password</a>
                    <a href="javascript:void(0);" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="icon ti ti-logout"></i> &nbsp;Logout
                    </a>
                    <form id="logout-form"
                        action="{{ Auth::guard('admin')->check() ? route('admin.logout') : (Auth::guard('manager')->check() ? route('manager.logout') : (Auth::guard('employee')->check() ? route('employee.logout') : '')) }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div>
            </div>
        </div>
    </div>
</header>
