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
                        @if ($isListForApproval)
                            <a href="{{ route('dividend.index') }}"> <button type="submit"
                                    class="btn btn-success mr-2">View
                                    Dividend List</button></a>
                        @else
                        <a href="{{ route('dividend.generate') }}"> <button type="submit"
                                class="btn btn-success mr-2">Generate Dividend</button></a>
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

                            <form action="insert.php" method="post">

                                <div class="row mb-4">
                                    <label for="horizontal-year-input" class="col-sm-3 col-form-label">Year</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="year" class="form-control" id="horizontal-year-input"
                                            placeholder="2023" value="{{$year ?? ''}}" {{$isListForApproval ? 'readonly':''}}>
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div class="">
                                            <button type="submit" name="search" id="search"
                                                class="btn btn-primary w-md {{$isListForApproval || isset($year) ? 'd-none':''}}">Search</button>
                                            <button type="button" name="approve" class="btn btn-success w-md {{$isListForApproval ? '':'d-none'}}"
                                                id="approve">Approve</button>
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
                                        <th>Share {{__('%')}}</th>
                                        <th>Total Contributions</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th class="{{$isListForApproval ? '':'d-none'}}">Action</th>
                                    </tr>
                                </thead>


                                <tbody id="t-body">
                                    @foreach($dividend_details as $dividend)
                                    @php
                    
                                        $share = ($dividend->amount/$total_amount) * 100;
                                        $total_contributions = \App\Models\Contribution::where('member_id', $dividend->member_id)->first()->balance;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dividend->member->name }}</td>
                                        <td>{{ number_format($dividend->amount, 2) }}</td>
                                        <td>{{ number_format($share,1) }}</td>
                                        <td>{{ number_format($dividend->total_contributions, 2) }}</td>
                                        <td>{{ $dividend->year }}</td>
                                        <td>{{ $dividend->is_approved ? 'Approved' : 'Unapproved'}}</td>
                                        <td class="{{$isListForApproval ? '':'d-none'}}">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">Action <i
                                                        class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu">
                                                    @if (!$dividend->is_approved)
                                                    <a class="dropdown-item" href="{{ route('dividend.approve_detail', $dividend->id) }}">Approve</a>
                                                    <a class="dropdown-item" href="{{ route('dividend.delete_detail', $dividend->id) }}">Delete</a>
                                                    @else
                                                    <a class="dropdown-item" href="{{ route('dividend.delete_detail', $dividend->id) }}">Delete</a>
                                                    @endif
                                                </div>
                                            </div><!-- /btn-group -->
                                        </td>
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
        $(document).ready(function() {
            $("#search").click(function(e) {
                e.preventDefault();
                //activate a loader for t-body table to show that data is loading
                $("#t-body").html('<tr><td colspan="7" class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

                var yr = $("#horizontal-year-input").val();
                //delay the ajax request for 3 second to allow the loader to show
                setTimeout(function() {
                    $.ajax({
                        url: "{{ route('dividend.search') }}",
                        type: "GET",
                        data: {
                            year: yr,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            //set html document title
                            document.title = 'Dividend List For '+yr;
                            $("#t-body").html(response.data);
                            
                        },
                    });
                }, 3000);
            });
        });
    

        $(document).ready(function() {
            $("#approve").click(function(e) {
                e.preventDefault();
                //activate the preloader
                $("#preloader").css("display", "block");

                var yr = $("#horizontal-year-input").val();
                //use sweet alet to confirm if user wants to approve
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to approve all generated annual dividend for the chosen year",
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
                                url: "{{ route('dividend.approve') }}",
                                type: "POST",
                                data: {
                                    year: yr,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    //check if response is success
                                    if (response.status == 'success') {
                                        Swal.fire(
                                            'Approved!',
                                            'All generated annual dividend for the chosen year has been approved.',
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
                                        document.title = 'Dividend List For '+yr;
    
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
@endsection
