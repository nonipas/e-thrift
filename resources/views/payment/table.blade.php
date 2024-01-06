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
                        @if(isset($search))
                            <a href="{{ route('payment.index') }}"> <button type="submit"
                                    class="btn btn-success mr-2">Search</button></a>
                        @else
                        <a href="{{ route('payment.batch') }}"> <button type="submit"
                            class="btn btn-success mr-2">back</button></a>
                        @endif

                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- table starts --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="datatable-buttons" class="table table-bordered dt-responsive  wrap w-100">
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
                                        <th class="{{isset($search)?'d-none':''}}">Action</th>
                                    </tr>
                                </thead>


                                <tbody>

                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> 
                                        <td>{{$payment->batch->name}}</td>
                                        <td>{{$payment->category->name}}</td>
                                        <td>{{$payment->beneficiary_name}}</td>
                                        <td>{{$payment->beneficiary_account_no}}</td>
                                        <td>{{\App\Helpers\Helpers::getBankName($payment->bank)}}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->description }}</td>
                                        <td><span class="p-2 text-{{$payment->is_approved == 1 ? 'success' : 'danger'}}">{{$payment->is_approved == 1 ? 'approved' : 'unapproved'}}</span></td>
                                    
                                        <td class="{{isset($search)?'d-none':''}}">
                                            <button type="button" class="btn btn-danger btn-sm {{$payment->is_approved == 1 ? 'd-none' : ''}}" onclick="deletePayment({{$payment->id}})">Delete </button>
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
        function deletePayment(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this payment?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('payment.delete', '') }}"+"/"+id;
                    loadUrl(url);
                }
            })
        }

    function loadUrl(url){
        //open the url
        window.location.href = url;
    }
    </script>
@endsection
