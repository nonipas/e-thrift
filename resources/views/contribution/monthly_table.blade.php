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
                        <a href="{{ route('contribution.generate') }}"> <button type="submit"
                                class="btn btn-success mr-2">Generate Monthly Contribution</button></a>

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
                                    <label for="horizontal-month-select" class="col-sm-3 col-form-label">Month</label>
                                    <div class="col-sm-9">
                                        <select name="month" id="horizontal-month-select" class="form-control select">
                                            <option value="">Select month</option>
                                            <option value="September">September</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-year-input" class="col-sm-3 col-form-label">Year</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="year" class="form-control" id="horizontal-year-input"
                                            placeholder="2023">
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div class="">
                                            <button type="submit" name="generate"
                                                class="btn btn-primary w-md">Search</button>
                                            <button type="button" name="approve" class="btn btn-success w-md"
                                                id="sa-warning">Approve</button>
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
                                        <th>Member</th>
                                        <th>Amount</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>


                                <tbody>

                                    <tr>
                                        <td>1</td>
                                        <td>Nonso Pascal</td>
                                        <td>{{ number_format('10000', 2) }}</td>
                                        <td>September</td>
                                        <td>2023</td>
                                        <td>Approved</td>
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
                        t.value && Swal.fire("Approved!", "Contributions for <strong>"+month+", "+yr+"</strong> approved successfully.", "success")
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
