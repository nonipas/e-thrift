@extends('layouts.app')
@section('title', $pageTitle)

@section('meta')

@endsection

@section('link')
    <!-- DataTables -->
    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet"
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
                        <a href="{{ route('contribution.add') }}"> <button type="submit" class="btn btn-primary mr-2">Add New
                            Contribution</button></a>


                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">{{ $pageTitle ?? '' }}</h4>
                            <div class="mb-2">
                                
                                <a href="{{ route('contribution.index') }}?status=active"> <button type="submit" class="btn btn-success mr-2">View Active
                                    Contributions</button></a>
                                <a href="{{ route('contribution.index') }}?status=inactive"> <button type="submit" class="btn btn-danger mr-2">View Inactive
                                    Contributions</button></a>
                            </div>

                            <table id="datatable-buttons" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Member</th>
                                        <th>Amount</th>
                                        <th>No of Months Contributed</th>
                                        <th>Total Contributions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($contributions as $contribution)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $contribution->member->name }}</td>
                                            <td>{{ number_format($contribution->amount) }}</td>
                                            
                                            <td>
                                                @php
                                                $no_of_months = \App\Models\MonthlyContributionDetail::where('member_id', $contribution->member_id)->where('is_approved', 1)->count();
                                                
                                                if($no_of_months > 0){
                                                    echo $no_of_months;
                                                } else {
                                                    echo '0';
                                                }
                                                @endphp
                                            </td>
                                            <td>{{ number_format($contribution->balance,2) }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">Action <i
                                                            class="mdi mdi-chevron-down"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item btn btn-primary waves-effect waves-light w-sm mr-2"
                                                            href="{{route('contribution.edit',['id'=>$contribution->id])}}">Edit</a>
                                                        <a class="dropdown-item" href="{{route('contribution.delete',['id'=>$contribution->id])}}">Delete</a>
                                                        

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
        </div> <!-- container-fluid -->
    </div>
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- Responsive examples -->
    <script src="{{asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatable init js -->
    <script src="{{asset('assets/js/pages/datatables.init.js')}}"></script>
@endsection
