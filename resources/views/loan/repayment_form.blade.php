@extends('layouts.app')
@section('title', $pageTitle)

@section('meta')

@endsection

@section('link')
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet') }}" type="text/css">
    <link href="{{ asset('assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/libs/%40chenfengyuan/datepicker/datepicker.min.css') }}">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }}</h4>

                        <a href="{{ route('loan.repayment') }}"> <button type="submit"
                                class="btn btn-success mr-2">View
                                Monthly Repayment List</button></a>


                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-xl-8" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $pageTitle ?? '' }}</h4>

                            <form action="{{route('loan.generate_monthly')}}" method="post">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-month-select" class="col-sm-3 col-form-label">Month</label>
                                    <div class="col-sm-9">
                                            <select name="month" id="horizontal-month-select"
                                                class="form-control select">
                                                <option value="">Select month</option>
                                                @foreach ($months as $month)
                                                    <option value="{{ $month->name }}">{{ $month->name }}</option>
                                                @endforeach
                                            </select>
                                        
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-year-input" class="col-sm-3 col-form-label">Year</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="year" class="form-control"
                                            id="horizontal-year-input" placeholder="2023">
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div>
                                            <button type="submit" name="generate"
                                                class="btn btn-primary w-md">Generate</button>
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
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40chenfengyuan/datepicker/datepicker.min.js') }}"></script>

    <!-- form advanced init -->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>



@endsection
