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
    <style>
        .img-box{
            position: relative;
            width: 100px;
            height: 100px;
        }
        .img-box img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .img-box .remove-img{
            position: absolute;
            top: 0;
            right: 0;
            background: #fff;
            padding: 5px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }}</h4>


                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-xl-8" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $pageTitle ?? '' }}</h4>

                            <form action="{{route('setting.update')}}" method="post">
                                @csrf
                                <div class="row mb-4">
                                    <label for="app-name" class="col-sm-3 col-form-label">App Name</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="setting[app_name]" class="form-control"
                                            id="app-name" placeholder=" " value="{{$settings_array['app_name']??''}}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="app-email" class="col-sm-3 col-form-label">App Email</label>
                                    <div class="col-sm-6">
                                        <input type="email" name="setting[app_email]" class="form-control"
                                            id="app-email" placeholder=" " value="{{$settings_array['app_email']??''}}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="app-phone" class="col-sm-3 col-form-label">App Phone</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="setting[app_phone]" class="form-control"
                                            id="app-phone" placeholder=" " value="{{$settings_array['app_phone']??''}}">
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <label for="app-address" class="col-sm-3 col-form-label">App Address</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="setting[app_address]" class="form-control"
                                            id="app-address" placeholder=" " value="{{$settings_array['app_address']??''}}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="app-logo-dark" class="col-sm-3 col-form-label">App Logo (dark)</label>
                                    {{-- display image isset settings_array--}}
                                    <div class="col-sm-6">
                                        <input type="file" name="setting[logo_dark]" class="form-control"
                                            id="app-logo-dark" placeholder=" " >
                                    </div>
                                    
                                    <div class="col-sm-3">
                                        <div class="img-box" id="logo-dark">
                                            @if (isset($settings_array['logo_dark']))
                                            <img src="{{asset('storage/'.$settings_array['logo_dark'])}}" alt="">
                                            <span class="remove-img"><i class="fa fa-times"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="app-logo-light" class="col-sm-3 col-form-label">App Logo (Light)</label>
                                    <div class="col-sm-6">
                                        <input type="file" name="setting[logo_light]" class="form-control"
                                            id="app-logo-light" placeholder=" " >
                                    </div>
                                    <div class="col-sm-3" >
                                        <div class="img-box" id="logo-light">
                                            @if (isset($settings_array['logo_light']))
                                            <img src="{{asset('storage/'.$settings_array['logo_light'])}}" alt="">
                                            <span class="remove-img"><i class="fa fa-times"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="app-icon-dark" class="col-sm-3 col-form-label">App Icon (dark)</label>
                                    <div class="col-sm-6">
                                        <input type="file" name="setting[icon_dark]" class="form-control"
                                            id="app-icon-dark" >
                                    </div>
                                    <div class="col-sm-3" >
                                        <div class="img-box" id="icon-dark">
                                            @if (isset($settings_array['icon_dark']))
                                            <img src="{{asset('storage/'.$settings_array['icon_dark'])}}" alt="">
                                            <span class="remove-img"><i class="fa fa-times"></i></span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-4">
                                    <label for="app-icon-light" class="col-sm-3 col-form-label">App Icon (Light)</label>
                                    <div class="col-sm-6">
                                        <input type="file" name="setting[icon_light]" class="form-control"
                                            id="app-icon-light" >
                                    </div>
                                    <div class="col-sm-3" >
                                        <div class="img-box" id="icon-light">
                                            @if (isset($settings_array['icon_light']))
                                            <img src="{{asset('storage/'.$settings_array['icon_light'])}}" alt="">
                                            <span class="remove-img"><i class="fa fa-times"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="app-favicon" class="col-sm-3 col-form-label">App Favicon</label>
                                    <div class="col-sm-6">
                                        <input type="file" name="setting[favicon]" class="form-control"
                                            id="app-favicon" placeholder=" " >
                                    </div>
                                    <div class="col-sm-3" >
                                        <div class="img-box" id="favicon">
                                            @if (isset($settings_array['favicon']))
                                            <img src="{{asset('storage/'.$settings_array['favicon'])}}" alt="">
                                            <span class="remove-img"><i class="fa fa-times"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                    

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div>
                                            <button type="submit" name="add-admin"
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
    {{-- <script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script> --}}
    <script>
        //function for display image
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                //validate image
                var validImageTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/svg', 'image/gif', 'image/icon', 'image/ico', 'image/webp'];
                if (!validImageTypes.includes(input.files[0]['type'])) {
                    swal.fire({
                        title: "Error!",
                        text: "Invalid image format",
                        icon: "error",
                        button: "Ok",
                    });
                    return false;
                }
                reader.onload = function(e) {
                    $('#' + id).html('<img src="' + e.target.result + '" alt=""> <span class="remove-img"><i class="fa fa-times"></i></span>');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#app-logo-dark").change(function() {
            readURL(this, 'logo-dark');
        });
        $("#app-logo-light").change(function() {
            readURL(this, 'logo-light');
        });
        $("#app-icon-dark").change(function() {
            readURL(this, 'icon-dark');
        });
        $("#app-icon-light").change(function() {
            readURL(this, 'icon-light');
        });
        $("#app-favicon").change(function() {
            readURL(this, 'favicon');
        });

        //remove image
        $(document).on('click', '.remove-img', function() {
            
            var id = $(this).parent().attr('id');
            $(this).parent().html('');
            $('#' + id).find('input').val('');
            
            
            if (id == 'logo-dark') {
                replaceInput($('#app-logo-dark'));
            } else if (id == 'logo-light') {
                replaceInput($('#app-logo-light'));
            } else if (id == 'icon-dark') {
                replaceInput($('#app-icon-dark'));
            } else if (id == 'icon-light') {
                replaceInput($('#app-icon-light'));
            } else if (id == 'favicon') {
                replaceInput($('#app-favicon'));
            } else {
                return false;
            }
        });


        //function to replace input
        function replaceInput(input) {
            var newFileInput = input.clone();
            input.replaceWith(newFileInput);
        }

    </script>
@endsection
