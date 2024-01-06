@extends('layouts.app')
@section('title', $pageTitle)

@section('meta')

@endsection

@section('link')
    <link href="{{asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/libs/spectrum-colorpicker2/spectrum.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/libs/%40chenfengyuan/datepicker/datepicker.min.css')}}">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }}</h4>

                        <a href="{{ route('profile.index') }}"> <button type="submit" class="btn btn-success mr-2">View
                                Profile</button></a>


                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-xl-5" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $pageTitle ?? '' }}</h4>

                                <form action="{{ route('profile.update_password') }}" method="post">
                           
                                    @csrf
                                
                                <div class="row mb-4">
                                    <label for="old" class="col-sm-3 col-form-label">Old password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="old_password" class="form-control" id="old" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="new" class="col-sm-3 col-form-label">New password</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control " id="new" required>
                                            <span class="input-group-text" onclick="viewPassword()">
                                                <i class="fa fa-eye-slash" id="eye"></i>
                                            </span>
                                        </div>

                                        
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="confirm" class="col-sm-3 col-form-label">Confirm password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="confirm_password" class="form-control" id="confirm" required >
                                        <span class="text-danger small" id="message"></span>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div>
                                            <button type="submit" name="submit"
                                                class="btn btn-primary w-md">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container-fluid -->
    </div>
@endsection

@section('script')
    <script src="{{asset('assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/spectrum-colorpicker2/spectrum.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('assets/libs/%40chenfengyuan/datepicker/datepicker.min.js')}}"></script>

    <!-- form advanced init -->
    <script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>
    <script>

        //function to view password input
        function viewPassword() {
            var x = document.getElementById("new");
            var y = document.getElementById("confirm");
            if (x.type === "password" && y.type === "password") {
                x.type = "text";
                y.type = "text";
            } else {
                x.type = "password";
                y.type = "password";
            }
        }

        //function to check if password match
        function checkPassword() {
            var password = document.getElementById("new").value;
            var confirm_password = document.getElementById("confirm").value;
            if (password != confirm_password) {
                document.getElementById("confirm").setCustomValidity("Passwords Don't Match");
                document.getElementById("message").innerHTML = "Passwords Don't Match";
            } else {
                document.getElementById("confirm").setCustomValidity('');
                document.getElementById("message").innerHTML = "";
            }
        }

        //call checkPassword function when confirm password is typed
        document.getElementById("confirm").onkeyup = checkPassword;
        
    </script>
@endsection
