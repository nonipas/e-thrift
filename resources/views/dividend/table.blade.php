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
                        <a href="{{ route('dividend.generate') }}"> <button type="submit"
                                class="btn btn-success mr-2">Generate Dividend</button></a>

                    </div>
                </div>
            </div>
            <!-- end page title -->

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
                                        <th>Year</th>
                                        <th>Total Members</th>
                                        <th>Total Amount Shared</th>
                                        <th>Dividend Total</th>
                                        <th>Status</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach($dividends as $dividend)
                                    @php
                                        $total_members = \App\Models\AnnualDividendDetail::where('annual_dividend_id', $dividend->id)->get()->count();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dividend->year }}</td>
                                        <td>{{ $total_members }}</td>
                                        <td>{{ number_format($dividend->total_amount,2) }}</td>
                                        <td>{{ number_format($dividend->total_dividend,2) }}</td>
                                        <td>{{ !$dividend->is_approved ? 'Unapproved':'Approved' }}</td>
                                        <td>
                                            @if (!$dividend->is_approved)
                                                <button class="btn btn-success btn-sm" onclick="approveDividend({{$dividend->id}})">Approve</button>
                                                <button class="btn btn-danger btn-sm" onclick="rejectDividend({{$dividend->id}})">Reject</button>
                                            @else
                                                <button class="btn btn-danger btn-sm" onclick="rejectDividend({{$dividend->id}})">Reject</button>
                                            @endif
                                            <a class="btn btn-primary btn-sm" href="{{ route('dividend.details',['id'=>$dividend->id]) }}">View Details</a>
                                    </tr>
                                    @endforeach
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
        function approveDividend(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this dividend?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#34c38f',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, Approve!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('dividend.approve_id', '') }}/"+id+"",
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Approved!',
                                    'Dividend approved successfully.',
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                })
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                )
                            }
                        }
                    });
                }
            })
        }

        function rejectDividend(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this dividend?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#34c38f',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, Reject!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('dividend.delete', '') }}/"+id+"",
                        type: "GET",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Rejected!',
                                    'Dividend rejected successfully.',
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                })
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                )
                            }
                        }
                    });
                }
            })
        }
    </script>
@endsection
