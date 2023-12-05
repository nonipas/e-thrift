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

            {{-- table starts --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="monthly">
                            <h4 class="card-title ">{{ $pageTitle ?? '' }}</h4>
                            <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100 ">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Total Amount</th>
                                        <th>No of Members</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($monthly_contributions as $monthly_contribution)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> <a href="{{ route('contribution.monthly_detail',['id'=>$monthly_contribution->id]) }}">{{$monthly_contribution->month}}</a> </td>
                                            <td>{{ $monthly_contribution->year }}</td>
                                            <td>{{ number_format($monthly_contribution->total_amount) }}</td>
                                            <td> 
                                                @php
                                                $no_of_members = \App\Models\MonthlyContributionDetail::where('monthly_contribution_id', $monthly_contribution->id)->count();
                                                
                                                if($no_of_members > 0){
                                                    echo $no_of_members;
                                                } else {
                                                    echo '0';
                                                }
                                                @endphp
                                            </td>
                                            <td>{{ !$monthly_contribution->is_approved ? 'Unapproved':'Approved' }}</td>
                                            <td>
                                                
                                                    @if (!$monthly_contribution->is_approved)
                                                        <button class="btn btn-success btn-sm" onclick="approveMonthlyContribution({{$monthly_contribution->id}})">Approve</button>
                                                    @else
                                                        <button class="btn btn-danger btn-sm" onclick="rejectMonthlyContribution({{$monthly_contribution->id}})">Reject</button>
                                                    @endif
                                                    <a class="btn btn-primary btn-sm" href="{{ route('contribution.monthly_detail',['id'=>$monthly_contribution->id]) }}">View Details</a>
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            </div>

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
  
       function loadUrl(url){
        //open the url
        window.location.href = url;
       }

       function approveMonthlyContribution(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to approve this monthly contribution",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6259ca',
            cancelButtonColor: '#6259ca',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('contribution.approve_monthly_id','') }}"+"/"+id;
                loadUrl(url);
            }
        })
       }

       function rejectMonthlyContribution(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to reject this monthly contribution",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6259ca',
            cancelButtonColor: '#6259ca',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('contribution.delete_monthly', '') }}"+"/"+id;
                loadUrl(url);
            }
        })
       }
    
    </script>

    <script>
       
    </script>
@endsection
