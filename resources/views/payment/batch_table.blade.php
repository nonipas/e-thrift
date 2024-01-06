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
                        @if($isListForApproval)
                            @if(isset($process))
                            <a href="{{ route('payment.approve_batches') }}"> <button type="submit" class="btn btn-success mr-2">View
                                    Approval List</button></a>
                            @else
                            <a href="{{ route('payment.batch') }}"> <button type="submit" class="btn btn-success mr-2">View
                                    Batch List</button></a>
                            @endif
                        @else
                        <a href="{{ route('payment.create-batch') }}"> <button type="submit" class="btn btn-success mr-2">Create New
                            Batch</button></a>
                        @endif


                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-9">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">{{ $pageTitle ?? '' }}</h4>

                            </p>
                            <form action="{{route('payment.approve_selected_batch')}}" method="post" id="batch-form">
                                @csrf

                            @if($isListForApproval)

                            <button type="button" name="approve" class="btn btn-primary my-2 mr-2 w-md approve-batch "
                                >Approve</button>
                            @if(isset($process))
                            <button type="button" name="process" class="btn btn-primary my-2 mr-2 w-md process-payment "
                                >Process</button>
                            <button type="button" name="reject_selected" class="btn btn-warning my-2 mr-2 w-md "
                                onclick="RejectSelected()">Reject Seleted</button>
                            @endif
                            @endif
                            <table id="datatable" class="table table-striped nowrap w-100">
                                <thead>
                                    <tr>
                                        @if($isListForApproval)
                                        <th>
                                            <input type="checkbox" name="check_all"
                                                    id="check-all" value="all" checked>      
                                        </th>
                                        @endif
                                        <th>S/N</th>
                                        <th>Batch Name</th>
                                        <th>Size</th>
                                        <th>Amount</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach($batches as $batch)
                                    <tr>
                                        @if($isListForApproval)
                                        <td>
                                            <input type="checkbox" class="check" name="payment_batches[]"
                                                     value="{{$batch->id}}" checked>
                                        </td>
                                        @endif
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$batch->name}}</td>
                                        <td>{{$batch->size}}</td>
                                        <td>{{number_format($batch->total_amount,2)}}</td>
                                        <td>
                                            @if($isListForApproval)
                                            <a class="btn btn-primary btn-sm mr-2 {{$batch->is_processed == 1 || $batch->is_approved == 0 ? 'd-none' : ''}}"
                                                href="{{route('payment.process',['id'=>$batch->id])}}">Process</a>
                                            
                                            <button class="btn btn-primary btn-sm mr-2 {{$batch->is_processed == 1 || $batch->is_approved == 1 ? 'd-none' : ''}}"
                                                onclick="approveBatch({{$batch->id}})" id="approve-batch">Approve</button>

                                            <a class="btn btn-warning btn-sm mr-2 {{$batch->is_processed == 1 || $batch->is_approved == 1 ? 'd-none' : ''}}"
                                                    href="{{route('payment.view_batch_approve',['id'=>$batch->id])}}">View</a>
                                            
                                            <button class="btn btn-danger btn-sm {{$batch->is_approved == 0 ? 'd-none':''}}" onclick="rejectBatch({{$batch->id}})"><i
                                                class="mdi mdi-delete"></i> Reject</button>
                                            @else
                                            
                                            <a class="btn btn-primary btn-sm mr-2 "
                                                href="{{route('payment.add_to_batch',['id'=>$batch->id])}}">Add Payment</a>
                                            <a class="btn btn-primary btn-sm waves-effect waves-light w-sm mr-2 {{$batch->size == 0 ? 'd-none' : ''}}" href="{{route('payment.view_batch',['id'=>$batch->id])}}"><i
                                                    class="mdi mdi-eye"></i> View</a>
                                            
                                            @endif
                                            <button class="btn btn-danger btn-sm {{$batch->is_processed == 1 || $batch->is_approved == 1 ? 'd-none' : ''}}" onclick="deleteBatch({{$batch->id}})"><i
                                                class="mdi mdi-delete"></i> Delete</button>
                                            
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            @if($isListForApproval)

                                <button type="button" name="approve" class="btn btn-primary my-2 mr-2 w-md approve-batch "
                                    >Approve</button>
                                @if(isset($process))
                                <button type="button" name="process" class="btn btn-primary my-2 mr-2 w-md process-payment "
                                    >Process</button>
                                <button type="button" name="reject_selected" class="btn btn-warning my-2 mr-2 w-md "
                                    onclick="RejectSelected()">Reject Seleted</button>
                                @endif
                            @endif
                            </form>

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
    {{-- <script src="{{asset('assets/js/pages/datatables.init.js')}}"></script> --}}
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

        function approveBatch(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve this batch?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('payment.approve_batch', '') }}"+"/"+id;
                    loadUrl(url);
                }
            })
        }

        function rejectBatch(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject this batch?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('payment.reject_batch', '') }}"+"/"+id;
                    loadUrl(url);
                }
            })
        }

        function deleteBatch(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this batch?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('payment.delete_batch', '') }}"+"/"+id;
                    loadUrl(url);
                }
            })
        }

        function loadUrl(url){
        //open the url
        window.location.href = url;
       }

@if($isListForApproval)
        $('.approve-batch').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve the selected batches?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#batch-form').submit();
                }
            })
        });
@endif

@if(isset($process))
       $('.process-batch').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to Process the selected batches?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, process it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    //ajax call to delete selected payments
                    var selected = [];
                    $('.check:checked').each(function() {
                        selected.push($(this).val());
                    });
                    $.ajax({
                        url: "{{ route('payment.process_selected_batch') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "payment_batches": selected
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Deleted!',
                                    'Batches rejected successfully.',
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                })
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong.',
                                    'error'
                                )
                            }
                        }
                    });
                }
            })
        });

        function rejectSelected(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to reject the selected batches?",
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
                        url: "{{ route('payment.reject_selected_batch') }}",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "payment_batches": selected
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire(
                                    'Deleted!',
                                    'Batches rejected successfully.',
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                })
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong.',
                                    'error'
                                )
                            }
                        }
                    });
                }
            })
        }
@endif
        
    </script>
@endsection
