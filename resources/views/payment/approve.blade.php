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
                        <a href="{{ route('payment.batch') }}"> <button type="submit" class="btn btn-success mr-2">Add new
                                payment</button></a>

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
                                    <label for="horizontal-category-select" class="col-sm-3 col-form-label">Select
                                        Batch</label>
                                    <div class="col-sm-9">
                                        <select name="category" id="horizontal-batch-select"
                                            class="select2 form-control select2-multiple" multiple="multiple"
                                            data-placeholder="Choose ...">
                                            <option value="0">All Batches</option>
                                            <option value="1">UW_COOP_BATCH_PAYMENT_1</option>
                                        </select>
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
                            
                            <table id="datatable-buttons" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="check_all"
                                                    id="check-all" value="all" checked>      
                                        </td>
                                        <th>S/N</th>
                                        <th>Batch Name</th>
                                        <th>Category</th>
                                        <th>Beneficiary</th>
                                        <th>Account No</th>
                                        <th>Bank Name</th>
                                        <th>Amount</th>
                                        <th>Narration</th>
                                        <th>Status</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i < 6; $i++)
                                    <tr>
                                        <td><input type="checkbox" name="payment_id" class="check"
                                            id="check-{{$i}}" value="{{$i}}" checked></td>
                                        <td>{{$i}}</td>
                                        <td>UW_COOP_BATCH_PAYMENT_{{$i}}</td>
                                        <td>Loan</td>
                                        <td>Nonso Pascal</td>
                                        <td>0123456789</td>
                                        <td>Union Bank</td>
                                        <td>{{ number_format('10000', 2) }}</td>
                                        <td>Loan payment</td>
                                        <td><span class="p-2 text-danger">unapproved</span></td>
                                        <td>2023/13/08 16:44</td>
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
                                    @endfor
                                </tbody>
                                <tfoot>
                                    <a href="{{ route('payment.batch') }}"> <button type="button" name="approve" class="btn btn-success w-md"
                                        id="sa-warning">Approve</button></a>
                                </tfoot>
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
                    var batch = t("#horizontal-batch-select").val();
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
                        t.value && Swal.fire("Approved!", "paynent for <strong>" + batch + 
                            "</strong> approved successfully.", "success")
                    })
                })
            }, t.SweetAlert = new e, t.SweetAlert.Constructor = e
        }(window.jQuery),
        function() {
            "use strict";
            window.jQuery.SweetAlert.init()
        }();
    </script>
    <script>
        $(document).ready(function() {
            $('#check-all').click(function() {
                if ($(this).is(':checked')) {
                    $('.check').prop('checked', true);
                } else {
                    $('.check').prop('checked', false);
                }
            });
            $('.check').click(function() {
                
                    $('#check-all').prop('checked', false);
                
            });
        });

    </script>
@endsection
