@extends('layouts.app')
@section('title', $pageTitle)

@section('meta')

@endsection

@section('link')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }}</h4>
                        <a href="{{ route('payment.batch') }}"> <button type="submit"
                                class="btn btn-success mr-2">Add new payment</button></a>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- form starts --}}
            <div class="row">

                <div class="col-xl-8" style="">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{route('payment.search')}}" method="post">
                                @csrf

                                <div class="row mb-4">
                                    <label for="horizontal-batch-input" class="col-sm-3 col-form-label">By Batch Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="batch_name" class="form-control" id="horizontal-batch-input"
                                            >
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-type-select" class="col-sm-3 col-form-label">By Payment Category</label>
                                    <div class="col-sm-9">
                                            <select name="category" id="horizontal-type-select"
                                                class="form-control select2">
                                                <option value="">Select</option>
                                                @foreach ($payment_categories as $category)
                                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-status-select" class="col-sm-3 col-form-label">By Payment Status</label>
                                    <div class="col-sm-9">
                                            <select name="status" id="horizontal-status-select"
                                                class="form-control select2">
                                                <option value="">Select</option>
                                                    <option value="pending" selected >Pending</option>
                                                    <option value="processed">Processed</option>
                                                    <option value="approved">Approved</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-ben-input" class="col-sm-3 col-form-label">By Beneficiary Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="beneficiary_name" class="form-control" id="horizontal-ben-input"
                                            >
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-account-input" class="col-sm-3 col-form-label">By Beneficiary Account</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="account" class="form-control" id="horizontal-account-input"
                                            >
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-bank-select" class="col-sm-3 col-form-label">By Bank</label>
                                    <div class="col-sm-9">
                                        <select name="bank" id="horizontal-bank-select" class="form-control select2">
                                            <option value="">Select</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->code }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="row mb-4">
                                    <label class="col-sm-3 col-form-label">By Date</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="row mb-4">
                                                    <label for="horizontal-datef-input" class="col-sm-3 col-form-label">From</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" name="date_from" class="form-control" id="horizontal-datef-input"
                                                            >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="row mb-4">
                                                    <label for="horizontal-datet-input" class="col-sm-3 col-form-label">To</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" name="date_to" class="form-control" id="horizontal-datet-input"
                                                            >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div class="">
                                            <button type="submit" name="search"
                                                class="btn btn-primary w-md">Search</button>
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
            {{-- form ends --}}

        </div> <!-- container-fluid -->
    </div>
@endsection

@section('script')

    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

    <script>
        ! function(t) {
            "use strict";

            function e() {}
            e.prototype.init = function() {
                t("#sa-warning").click(function() {
                    var month = t("#horizontal-month-select").val();
                    var yr = t("#horizontal-year-input").val();
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#34c38f",
                        cancelButtonColor: "#f46a6a",
                        confirmButtonText: "Yes, Approve!"
                    }).then(function(t) {
                        t.value && Swal.fire("Approved!", "Repaynent for <strong>"+month+", "+yr+"</strong> approved successfully.", "success")
                    })
                })
            }, t.SweetAlert = new e, t.SweetAlert.Constructor = e
        }(window.jQuery),
        function() {
            "use strict";
            window.jQuery.SweetAlert.init()
        }();
    </script>
@endsection
