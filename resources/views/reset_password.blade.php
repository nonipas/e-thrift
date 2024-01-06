<!doctype html>
<html lang="en">

<head>
        
        <meta charset="utf-8" />
        <title>Reset Password | E-thrift and Cooperative Management System</title>
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
                                                <h5 class="text-primary"> Reset Password</h5>
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
                                                    <img src="{{url('/')}}/assets/images/logo.svg" alt="" class="rounded-circle" height="34">
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    
                                    <div class="p-2">
                                        <div class="alert alert-info text-center mb-4" role="alert">
                                            Enter your new password and confirm password to reset your password.
                                        </div>
                                        <form class="form-horizontal" action="{{route('store-password-reset')}}" method="POST">
                                            @csrf
                                            <input type="hidden"  name="email" value="{{$token->email??''}}">
                
                                            <div class="mb-3">
                                                <label for="pass" class="form-label">Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="password" class="form-control" id="pass" >
                                                    <span class="input-group-text" id="basic-addon2" onclick="viewPassword()"><i class="mdi mdi-eye-outline"></i></span>
                                                </div>

                                            </div>

                                            <div class="mb-3">
                                                <label for="pass2" class="form-label">Confirm Password</label>
                                                <input type="password" name="confirm_password" class="form-control" id="pass2" >
                                                <span class="text-danger small" id="message"></span>
                                            </div>
                        
                                            <div class="text-end">
                                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Reset</button>
                                            </div>
        
                                        </form>
                                    </div>
                
                                </div>
                            </div>
                            <div class="mt-5 text-center">
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

        <script>
            function viewPassword(){
                var passwordInput = document.getElementById('pass');
                var passStatus = document.getElementById('basic-addon2');
                if (passwordInput.type == 'password'){
                    passwordInput.type='text';
                    passStatus.innerHTML = '<i class="mdi mdi-eye-off-outline"></i>';
                }else{
                    passwordInput.type='password';
                    passStatus.innerHTML = '<i class="mdi mdi-eye-outline"></i>';
                }
            }

            //check if password and confirm password match
            function checkPassword(){
                var password = document.getElementById('pass').value;
                var confirm_password = document.getElementById('pass2').value;
                if (password != confirm_password){
                    document.getElementById('message').innerHTML = 'Password does not match';
                }
            }

            //call checkPassword function when confirm password field is typed
            document.getElementById('pass2').addEventListener('keyup', function(){
                checkPassword();
            });
        </script>

    </body>

<!-- Mirrored from themesbrand.com/skote-symfony/layouts/auth-login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 31 Mar 2022 14:29:28 GMT -->
</html>
