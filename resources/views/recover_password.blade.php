<!doctype html>
<html lang="en">

@if (session('data'))
    @php
        $data = session('data');
    @endphp
@endif

<head>
        
        <meta charset="utf-8" />
        <title>Recover Password | E-thrift and Cooperative Management System</title>
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

    <body>
        

            <div class="account-pages my-5 pt-sm-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card overflow-hidden">
                                <div class="bg-primary bg-soft">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="text-primary p-4">
                                                <h5 class="text-primary"> Recover Password</h5>
                                            </div>
                                        </div>
                                        <div class="col-5 align-self-end">
                                            <img src="{{url('/')}}/assets/images/profile-img.png" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0"> 
                                    <div>
                                        <a href="{{url('/')}}">
                                            <div class="avatar-md profile-user-wid mb-4">
                                                <span class="avatar-title rounded-circle bg-light">
                                                    <img src="{{asset('assets/images/logo/').'/'.(App\Helpers\Helpers::getConfig('icon_dark')??'icon-dark.svg')}}" alt="" class="rounded-circle" height="34">
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <div class="p-2">
                                        <div class="alert alert-info text-center mb-4" role="alert">
                                            Enter your Email and instructions will be sent to you!
                                        </div>
                                        <form class="form-horizontal" action="{{route('send-reset-password-link')}}" method="POST">
                                            @csrf
                
                                            <div class="mb-3">
                                                <label for="useremail" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="{{$data['email'] ?? ''}}" id="useremail" placeholder="Enter email">
                                            </div>
                        
                                            <div class="text-end">
                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">{{isset($data)?'Resend':'Send'}}</button>
                                            </div>
        
                                        </form>
                                    </div>
                
                                </div>
                            </div>
                            <div class="mt-5 text-center">
                                <p>Remember It ? <a href="{{route('login')}}" class="fw-medium text-primary"> Sign In here</a> </p>
                                <p>© <script>document.write(new Date().getFullYear())</script> E-thrift. Crafted with <i class="mdi mdi-heart text-danger"></i> by Nosprodev</p>
                            </div>
    
                        </div>
                    </div>
                </div>
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
</html>
