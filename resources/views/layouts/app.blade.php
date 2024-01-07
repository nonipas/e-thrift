
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>E-thrift Management - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Thrift and Cooperative Society management system" name="description" />
    <meta content="Nosprodev" name="author" />
    @yield('meta')
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
    
    <!-- Sweet Alert-->
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    
    <!-- third party css -->
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    @yield('link')
    <style>

        #preloader {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 999;
            width: 100%;
            height: 100%;
            overflow: visible;
            background: rgba(255, 255, 255, .8) url("{{ asset('assets/images/loader.gif') }}") no-repeat center center;
        }
    
    </style>
    
</head>

<body data-sidebar="dark">

    
    <!-- Begin page -->
    <div id="layout-wrapper">

        {{-- header section start --}}
        <header id="page-topbar">

            {{-- navbar section start --}}
            @include('inc.nav')
            {{-- navbar section end --}}

        </header>
        {{-- header section end --}}

        {{-- sidebar section start --}}
        @include('inc.sidebar')
        {{-- sidebar section end --}}

        {{-- main content start --}}
        <main class="main-content">

            @yield('content')

            {{-- footer section start --}}
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © E-thrift Dashboard
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Developed by <a href="#">Nosprodev</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            {{-- footer section end --}}
            <div id="preloader" >
                <div class="spinner-border color-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </main>

        {{-- main section end --}}

    </div>
    @include('notify::components.notify')
    {{-- END layout-wrapper --}}
    <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>  
  
    <!-- Sweet Alerts js -->
    <script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Bootstrap Toasts Js -->
    {{-- <script src="{{asset('assets/js/pages/bootstrap-toastr.init.js')}}"></script> --}}

    @yield('script')
    <script src="{{asset('assets/js/app.js')}}"></script>
    @if(isset($message))
        {{App\Helpers\Helpers::getToastr($message)}}
    @endif

    @if(session('message'))
        {{App\Helpers\Helpers::getToastr(session ('message'))}}
    @endif
</body>

</html>
