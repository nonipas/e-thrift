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

    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
    @yield('link')

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
            
        </main>

        {{-- main section end --}}

    </div>
    {{-- END layout-wrapper --}}
    <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    @yield('script')
    <script src="{{asset('assets/js/app.js')}}"></script>
    
</body>

</html>
