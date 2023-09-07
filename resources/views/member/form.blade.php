@extends('layouts.app')
@section('title', $pageTitle)

@section('meta')

@endsection

@section('link')
    <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
    <link href="assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css">
    <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/libs/%40chenfengyuan/datepicker/datepicker.min.css">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }}</h4>

                        <a href="{{ route('member.index') }}"> <button type="submit" class="btn btn-success mr-2">View
                                Members List</button></a>


                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-xl-8" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $pageTitle ?? '' }}</h4>

                            <form action="insert.php" method="post">
                                <div class="row mb-4">
                                    <label for="horizontal-account-input" class="col-sm-3 col-form-label">Account No</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="account_no" class="form-control"
                                            id="horizontal-account-input" placeholder="0123456789 ">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-bank-select" class="col-sm-3 col-form-label">Bank</label>
                                    <div class="col-sm-9">
                                        <select name="bank" id="horizontal-bank-select" class="form-control select2">
                                            <option>Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-name-input" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control" id="horizontal-name-input"
                                            placeholder="Uche John">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-phone-input" class="col-sm-3 col-form-label">Phone no:</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone" class="form-control"
                                            id="horizontal-phone-input" placeholder="08012345678">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-dept-select" class="col-sm-3 col-form-label">Department</label>
                                    <div class="col-sm-9">
                                        <select name="depatment" id="horizontal-dept-select" class="form-control select2">
                                            <option>Select</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-amount-input" class="col-sm-3 col-form-label">Monthly
                                        Contribution</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="contribution_amount" class="form-control"
                                            id="horizontal-amount-input" placeholder="1000">
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
    <script src="assets/libs/select2/js/select2.min.js"></script>
    <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="assets/libs/%40chenfengyuan/datepicker/datepicker.min.js"></script>

    <!-- form advanced init -->
    <script src="assets/js/pages/form-advanced.init.js"></script>
@endsection
