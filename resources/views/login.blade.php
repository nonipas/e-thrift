<!doctype html>
<html lang="en">
<head>
        
        <meta charset="utf-8" />
        <title>Login | E-thrift and Cooperative Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Management Solution for Cooperative Societies to manage loans, contribution and dividends" name="description" />
        <meta content="Nosprodev" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.ico">

        <!-- owl.carousel css -->
        <link rel="stylesheet" href="{{url('/')}}/assets/libs/owl.carousel/assets/owl.carousel.min.css">

        <link rel="stylesheet" href="{{url('/')}}/assets/libs/owl.carousel/assets/owl.theme.default.min.css">

        <!-- Bootstrap Css -->
        <link href="{{url('/')}}/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{url('/')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{url('/')}}/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body class="auth-body-bg">
        
        <div>
            <div class="container-fluid p-0">
                <div class="row g-0">
                    
                    <div class="col-xl-8">
                        <div class="auth-full-bg pt-lg-5 p-4">
                            <div class="w-100">
                                <div class="bg-overlay"></div>
                                <div class="d-flex h-100 flex-column">
                                    <div class="justify-content-center">
                                        <div class="text-center pt-4 z-index-1000">
                                            <h2 class="mb-3 text-white text-uppercase">E-thrift Cooperative Society Management System</h2>
                                        </div>
                                    </div>
    
                                    <div class="p-4 mt-auto">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-7">
                                                <div class="text-center z-index-1000">

                                                    
                                                    <div dir="ltr"> 
                                                        <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">
                                                            <div class="item">
                                                                <div class="py-3">
                                                                    {{-- Our system is designed to efficiently manage all aspects of your cooperative, including members, contributions, loans, and payments. With our user-friendly interface and powerful features, you can easily keep track of your members, their contributions, and their loan activities. Our system also provides seamless payment management, ensuring that all transactions are processed smoothly and securely.  --}}
                                                                    <p class="font-size-14 mb-4 text-white">Experience the convenience and effectiveness of our cooperative soceity management system and take your cooperative soceity to new heights of success.</p>
    
                                                                    {{-- <div>
                                                                        <h4 class="font-size-16 text-primary">Abs1981</h4>
                                                                        <p class="font-size-14 mb-0">- Skote User</p>
                                                                    </div> --}}
                                                                    <div>
                                                                        <p class="font-size-14 text-primary mb-0">Members management <span class="text-white mr-2">|</span> Contribution management <span class="text-white mr-2">|</span> Loan management <span class="text-white mr-2">|</span> Payment management</p>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
    
                                                            {{-- <div class="item">
                                                                <div class="py-3">
                                                                    <p class="font-size-16 mb-4">" If Every Vendor on Envato are as supportive as Themesbrand, Development with be a nice experience. You guys are Wonderful. Keep us the good work. "</p>
    
                                                                    <div>
                                                                        <h4 class="font-size-16 text-primary">nezerious</h4>
                                                                        <p class="font-size-14 mb-0">- Skote User</p>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xl-4">
                        <div class="auth-full-page-content p-md-5 p-4">
                            <div class="w-100">

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-2 mb-md-5">
                                        <a href="{{url('/')}}" class="d-block auth-logo">
                                            <img src="{{asset('assets/images/logo').'/'.(App\Helpers\Helpers::getConfig('logo_light') ?? 'logo-dark.svg')}}" alt="" height="40" class="auth-logo-dark">
                                            <img src="{{asset('assets/images/logo').'/'.(App\Helpers\Helpers::getConfig('logo_light') ?? 'logo-light.svg')}}" alt="" height="40" class="auth-logo-light">
                                        </a>
                                    </div>
                                    <div class="my-auto">
                                        
                                        <div>
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p class="text-muted">Sign in to continue to E-thrift.</p>
                                        </div>
            
                                        <div class="mt-4">
                                            <form action="{{route('login_submit')}}" method="POST">
                                                @csrf
                
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="username" name="email" placeholder="Enter email" autofocus>
                                                </div>
                        
                                                <div class="mb-3">
                                                    <div class="float-end">
                                                        <a href="{{route('recover-password')}}" class="text-muted">Forgot password?</a>
                                                    </div>
                                                    <label class="form-label">Password</label>
                                                    <div class="input-group auth-pass-inputgroup">
                                                        <input type="password" name="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                                                        <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>
                                                </div>
                        
                                                {{-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                                    <label class="form-check-label" for="remember-check">
                                                        Remember me
                                                    </label>
                                                </div> --}}
                                                
                                                <div class="mt-3 d-grid">
                                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                                </div>

                                            </form>
                                            {{-- <div class="mt-5 text-center">
                                                <p>Don't have an account ? <a href="{{url('/')}}/auth-register-2.html" class="fw-medium text-primary"> Signup now </a> </p>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> E-thrift. Crafted with <i class="mdi mdi-heart text-danger"></i> by Nosprodev</p>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{url('/')}}/assets/libs/jquery/jquery.min.js"></script>
        <script src="{{url('/')}}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="{{url('/')}}/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="{{url('/')}}/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="{{url('/')}}/assets/libs/node-waves/waves.min.js"></script>

        <!-- owl.carousel js -->
        <script src="{{url('/')}}/assets/libs/owl.carousel/owl.carousel.min.js"></script>

        <!-- auth-2-carousel init -->
        <script src="{{url('/')}}/assets/js/pages/auth-2-carousel.init.js"></script>
        
        <!-- App js -->
        <script src="{{url('/')}}/assets/js/app.js"></script>

    </body>

<!-- Mirrored from themesbrand.com/skote-symfony/layouts/auth-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 31 Mar 2022 14:29:28 GMT -->
</html>
