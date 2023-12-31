<nav class="navbar-header">
    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="{{url('/')}}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{asset('assets/images/logo').'/'.(App\Helpers\Helpers::getConfig('icon_dark')??'icon-dark.svg')}}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('assets/images/logo').'/'.(App\Helpers\Helpers::getConfig('logo_dark')??'logo-dark.svg')}}" alt="" height="17">
                </span>
            </a>

            <a href="{{url('/')}}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{asset('assets/images/logo/').'/'.(App\Helpers\Helpers::getConfig('icon_light')??'icon-light.svg')}}" alt="" height="32">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('assets/images/logo/').'/'.(App\Helpers\Helpers::getConfig('logo_light')??'logo-light.svg')}}" alt="" height="44">
                </span>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
            <i class="fa fa-fw fa-bars"></i>
        </button>

        <!-- App Search-->
    </div>

    <div class="d-flex">

        <div class="dropdown d-none d-lg-inline-block ms-1">
            <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                <i class="bx bx-fullscreen"></i>
            </button>
        </div>

        {{-- <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item noti-icon waves-effect"
                id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="bx bx-bell bx-tada"></i>
                <span class="badge bg-danger rounded-pill">3</span>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                aria-labelledby="page-header-notifications-dropdown">
                <div class="p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0" key="t-notifications"> Notifications </h6>
                        </div>
                        <div class="col-auto">
                            <a href="#!" class="small" key="t-view-all"> View All</a>
                        </div>
                    </div>
                </div>

                <div class="p-2 border-top d-grid">
                    <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                        <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View
                            More..</span>
                    </a>
                </div>
            </div>
        </div> --}}

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="{{asset('assets/images/users/avatar.jpg')}}"
                    alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{auth()->user()->name ??''}}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <a class="dropdown-item" href="{{route('profile.index')}}"><i
                        class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="{{route('logout')}}"><i
                        class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span
                        key="t-logout">Logout</span></a>
            </div>
        </div>

        {{-- <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                <i class="bx bx-cog bx-spin"></i>
            </button>
        </div> --}}

    </div>
</nav>
