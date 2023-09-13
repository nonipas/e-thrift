<nav class="navbar-header">
    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="{{url('/')}}" class="logo logo-dark">
                <span class="logo-sm">
                    E-thrift
                    {{-- <img src="{{asset('assets/images/logo.svg" alt="" height="22"> --}}
                </span>
                <span class="logo-lg">
                    E-thrift
                    {{-- <img src="{{asset('assets/images/logo-dark.png" alt="" height="17"> --}}
                </span>
            </a>

            <a href="{{url('/')}}" class="logo logo-light">
                <span class="logo-sm">
                    E-thrift
                    {{-- <img src="{{asset('assets/images/logo-light.svg" alt="" height="22"> --}}
                </span>
                <span class="logo-lg">
                    E-thrift
                    {{-- <img src="{{asset('assets/images/logo-light.png" alt="" height="19"> --}}
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
                <img class="rounded-circle header-profile-user" src="{{asset('assets/images/users/avatar-1.jpg')}}"
                    alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">Henry</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <a class="dropdown-item" href="contacts-profile.html"><i
                        class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                <a class="dropdown-item" href="crypto-wallet.html"><i
                        class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">My
                        Wallet</span></a>
                <a class="dropdown-item" href="auth-lock-screen.html"><i
                        class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-lock-screen">Lock
                        screen</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="auth-login.html"><i
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
