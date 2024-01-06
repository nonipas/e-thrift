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
                        @if($list)
                        <a href="{{ route('contribution.generate') }}"> <button type="submit"
                                class="btn btn-success mr-2">Generate Monthly Contribution</button></a>
                        @else
                        <a href="{{ route('contribution.approve_monthly') }}"> <button type="submit" 
                                class="btn btn-success mr-2">View Monthly Approval List</button></a>
                        @endif

                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- form starts --}}
            <div class="row">

                <div class="col-xl-8" style="">
                    <div class="card">
                        <div class="card-body">

                            <form action="" method="post" id="approve-form">

                                <div class="row mb-4">
                                    <label for="horizontal-month-select" class="col-sm-3 col-form-label">Month</label>
                                    <div class="col-sm-9">
                                        <select name="month" id="horizontal-month-select" class="form-control select" {{$list ? '':'disabled'}}>
                                            <option value="">Select month</option>
                                            @foreach ($months as $month)
                                                <option value="{{ $month->name }}" {{$month->name == $data['month'] ? 'selected':''}}>{{ $month->name }}</option>
                                            @endforeach
                                        </select>
                                        @if (!$list)
                                            <input type="hidden" name="month" value="{{$data['month']}}">
                                        @endif

                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-year-input" class="col-sm-3 col-form-label">Year</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="year" class="form-control" id="horizontal-year-input"
                                            placeholder="2023" value="{{$data['year']}}" {{$list ? '':'readonly'}}>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div class="">
                                            <button type="submit" name="generate"
                                                class="btn btn-primary w-md {{$list ? '':'d-none'}}" id="search" >Search</button>
                                            
                                            <button type="button" name="approve" class="btn btn-success w-md {{$list ? 'd-none':''}}"
                                                id="approve">Approve All</button>
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
                            <div class="monthly">
                           
                            </div>
                            <h4 class=" mt-3 card-title">{{ $pageTitle ?? '' }} details</h4>
                            <table id="datatable-buttons" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Member</th>
                                        <th>Amount</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Status</th> 
                                        @if($list)
                                        <th>Approved By</th>
                                        <th>Date Approved</th>
                                        @endif
                                        <th class="{{$list ? 'd-none':''}}">Action</th>
                                    </tr>
                                </thead>


                                <tbody id="t-body">

                                    @if (count($monthly_contribution_details) > 0)
                                    @foreach ($monthly_contribution_details as $monthly_contribution_detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $monthly_contribution_detail->member->name }}</td>
                                            <td>{{ number_format($monthly_contribution_detail->amount) }}</td>
                                            <td>{{ $monthly_contribution_detail->month }}</td>
                                            <td>{{ $monthly_contribution_detail->year }}</td>
                                            <td>{{ !$monthly_contribution_detail->is_approved ? 'Unapproved':'Approved' }}</td>
                                            @if($list)
                                            <td>{{ \App\Models\User::where('id',$monthly_contribution_detail->approved_by)->first()->name ?? '' }}</td>
                                            <td>{{ $monthly_contribution_detail->approved_at ? date('d M Y H:i:s',strtotime($monthly_contribution_detail->approved_at)) : '' }}</td>
                                            @endif
                                            <td class="{{$list ? 'd-none':''}}">
                                                    @if (!$monthly_contribution_detail->is_approved)
                                                        <a class="btn btn-success btn-sm"
                                                            href="{{ route('contribution.approve_monthly_member', $monthly_contribution_detail->id) }}">Approve</a>
                                                        <a class="btn btn-danger btn-sm"
                                                            href="{{ route('contribution.delete_monthly_detail', $monthly_contribution_detail->id) }}">Delete</a>
                                                            @else
                                                        <a class="btn btn-danger btn-sm"
                                                            href="{{ route('contribution.delete_monthly_detail', $monthly_contribution_detail->id) }}">Reject</a>
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    
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
  
    //function to get all generated monthly contribution when search button is clicked
    $(document).ready(function() {
        $("#search").click(function(e) {
            e.preventDefault();
            //activate a loader for t-body table to show that data is loading
            $("#t-body").html('<tr><td colspan="7" class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
            var month = $("#horizontal-month-select").val();
            var yr = $("#horizontal-year-input").val();
            //delay the ajax request for 3 second to allow the loader to show
            setTimeout(function() {
                $.ajax({
                    url: "{{ route('contribution.search_monthly') }}",
                    type: "GET",
                    data: {
                        month: month,
                        year: yr,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        //hide class monthly
                        $(".monthly").hide();
                        $("#t-body").html(response.data);
                    },
                });
            }, 3000);
        });
    });

    //function to approve all generated monthly contribution when approve button is clicked
    $(document).ready(function() {
        $("#approve").click(function(e) {
            e.preventDefault();
            //activate the preloader
            $("#preloader").css("display", "block");

            var month = $("#horizontal-month-select").val();
            var yr = $("#horizontal-year-input").val();
            //use sweet alet to confirm if user wants to approve
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve all generated monthly contribution for the selected month and year",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    //delay the ajax request for 3 second to allow the loader to show
                    setTimeout(function() {
                        $.ajax({
                            url: "{{ route('contribution.approve_monthly') }}",
                            type: "POST",
                            data: {
                                month: month,
                                year: yr,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                //check if response is success
                                if (response.status == 'success') {
                                    Swal.fire(
                                        'Approved!',
                                        'All generated monthly contribution for the selected month and year has been approved.',
                                        'success'
                                    )
                                }else{
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    )
                                }
                                //hide the preloader
                                $("#preloader").css("display", "none");
                                //activate a loader for t-body table to show that data is loading and delay for 3 seconds
                                $("#t-body").html('<tr><td colspan="7" class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                                setTimeout(function() {
                                    
                                    $("#t-body").html(response.data);
                                    //set html document title
                                    document.title = 'Monthly Contribution List For '+month+' '+yr;

                                }, 3000);
                            },
                        });
                    }, 3000);
                }
                $("#preloader").css("display", "none");
            })
        });
    }); 
    
    </script>

    <script>
       
    </script>
@endsection
