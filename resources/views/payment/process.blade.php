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
                        <a class="btn btn-warning btn-sm" href="{{ route('payment.process_batches') }}">back</a>
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
                            <h4 class="card-title">{{ $pageTitle ?? '' }}</h4>
                            <form action="{{route('payment.process_selected')}}" method="post" id="payment-form">
                                @csrf
                                <input type="hidden" name="batch_id" value="{{$batch->id}}">
                            <button type="button" name="approve" class="btn btn-primary my-2 mr-2 w-md process-payment {{$batch->is_processed == 1 ? 'd-none' : ''}}"
                                    >Process</button>
                            <button type="button" name="reject_selected" class="btn btn-warning my-2 mr-2 w-md {{$batch->is_processed == 1 ? 'd-none' : ''}}"
                                    onclick="rejectSelected()">Reject Seleted</button>
                            <table id="datatable" class="table table-striped dt-responsive  wrap w-100">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="check_all"
                                                    id="check-all" value="all" checked>      
                                        </th>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                    <tr>
                                        <td><input type="checkbox" name="payments[]" class="check"
                                            id="check-{{$payment->id}}" value="{{$payment->id}}" checked></td>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$payment->batch->name}}</td>
                                        <td>{{$payment->category->name}}</td>
                                        <td>{{$payment->beneficiary_name}}</td>
                                        <td>{{$payment->beneficiary_account_no}}</td>
                                        <td>{{\App\Helpers\Helpers::getBankName($payment->bank)}}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{$payment->description}}</td>
                                        <td><span class="p-2 text-{{$payment->is_processed == 1 ? 'success' : 'primary'}}">{{$payment->is_processed == 1 ? 'Processed' : 'Approved'}}</span></td>
                                        <td>{{date('d M Y H:i:s', strtotime($payment->approved_at))}}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm {{$payment->is_processed == 1 ? 'd-none' : ''}}" onclick="rejectPayment({{$payment->id}})">Reject </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                    
                            </table>
                            <button type="button" name="approve" class="btn btn-primary my-2 mr-2 w-md process-payment {{$batch->is_processed == 1 ? 'd-none' : ''}}"
                                >Process</button>
                            <button type="button" name="reject_selected" class="btn btn-warning my-2 mr-2 w-md {{$batch->is_processed == 1 ? 'd-none' : ''}}"
                                onclick="rejectSelected()">Reject Seleted</button>
                        </form>
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
    {{-- <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script> --}}

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({paging: false,searching: true,scrollY: "400px",scrollCollapse:true});

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

        //function to confirm before submitting form
        $('.process-payment').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to Process the selected payment?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, process it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#payment-form').submit();
                }
            })
        });


        function rejectPayment(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this payment?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('payment.reject', '') }}"+"/"+id;
                    loadUrl(url);
                }
            })
        }

        function rejectSelected(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject the selected payments?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, reject them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    //ajax call to delete selected payments
                    var selected = [];
                    $('.check:checked').each(function() {
                        selected.push($(this).val());
                    });
                    $.ajax({
                        url: "{{ route('payment.reject_selected') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "batch_id": "{{$batch->id}}",
                            "payments": selected
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Rejected!',
                                    'Payments rejected successfully.',
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

    function loadUrl(url){
        //open the url
        window.location.href = url;
    }

    </script>
@endsection
