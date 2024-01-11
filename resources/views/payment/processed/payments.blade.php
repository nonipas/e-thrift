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
                    <div >
                        <a class="btn btn-warning btn-sm" href="{{ route('payment.search_processed') }}">back</a>
                    </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- table starts --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <h4 class="card-title mb-3">{{ $pageTitle ?? '' }} <a href="{{route('payment.export_processed',['batch_id'=>$batch->id])}}" ><button type="submit" class="btn btn-success" >
                                Export to Excel
                            </button></a></h4>
                            

                            <table id="datatable-buttons" class="table table-striped dt-responsive  wrap w-100">
                                <thead>
                                    
                                    <tr>
                                        <th>Beneficiary</th>
                                        <th>Bank Code</th>
                                        <th>Account No</th>
                                        <th>Amount</th>
                                        <th>Narration</th>
                                        {{-- <th>Bank Name</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{strtoupper($payment->beneficiary_name)}}</td>
                                        <td>{{$payment->bank}}</td>
                                        <td>{{$payment->beneficiary_account_no}}</td>
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{strtoupper($payment->description)}}</td>
                                        {{-- <td>{{strtoupper(\App\Helpers\Helpers::getBankName($payment->bank))}}</td> --}}
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
        // $(document).ready(function() {
        //     $('#datatable').DataTable({paging: false,searching: true,scrollY: "400px",scrollCollapse:true,buttons: ['copy', 'excel', 'pdf', 'print']});
        // });

    </script>
@endsection
