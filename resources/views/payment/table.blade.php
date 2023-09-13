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
                        <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }} Search</h4>
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

                            <form action="insert.php" method="post">

                                <div class="row mb-4">
                                    <label for="horizontal-batch-input" class="col-sm-3 col-form-label">By Batch Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="batch_name" class="form-control" id="horizontal-batch-input"
                                            >
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-category-select" class="col-sm-3 col-form-label">By Payment Category</label>
                                    <div class="col-sm-9">
                                            <select name="category" id="horizontal-type-select"
                                                class="form-control select2">
                                                <option>Select</option>
                                                <option value="loan">Loan payout</option>
                                                <option value="dividend">Dividend</option>
                                                <option value="refund">Refund</option>
                                                <option value="other">Other</option>
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
                                            <option>Select</option>
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
                                            <button type="submit" name="generate"
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

            {{-- table starts --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">{{ $pageTitle ?? '' }}</h4>

                            </p>

                            <table id="datatable-buttons" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Batch Name</th>
                                        <th>Category</th>
                                        <th>Beneficiary</th>
                                        <th>Account No</th>
                                        <th>Bank Name</th>
                                        <th>Amount</th>
                                        <th>Narration</th>
                                        <th>Status</th>
                                        <th>Date Approved</th>
                                        <th>Date Processed</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>


                                <tbody>

                                    <tr>
                                        <td>1</td>
                                        <td>UW_COOP_BATCH_PAYMENT_1</td>
                                        <td>Loan</td>
                                        <td>Nonso Pascal</td>
                                        <td>0123456789</td>
                                        <td>Union Bank</td>
                                        <td>{{ number_format('10000', 2) }}</td>
                                        <td>Loan payment</td>
                                        <td>Processed</td>
                                        <td>2023/13/08 16:44</td>
                                        <td>2023/13/09 16:44</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">Action <i
                                                        class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu">
                                                    {{-- <a class="dropdown-item btn btn-primary waves-effect waves-light w-sm mr-2"
                                                        href="#">Edit</a> --}}
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div><!-- /btn-group -->
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
            {{-- table ends --}}

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
