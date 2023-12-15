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
                        <a href="{{ route('loan.add') }}"> <button type="submit" class="btn btn-success mr-2">Add New
                            Loan</button></a>


                    </div>
                </div>
            </div>
            <!-- end page title -->

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
                                        <th>Beneficiary</th>
                                        <th>Loan Amount</th>
                                        <th>Monthly Repayment</th>
                                        <th>Total Repayment</th>
                                        <th>No of Months Paid</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>


                                <tbody>

                                    @foreach ($loans as $loan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $loan->user->name }}</td>
                                            <td>{{ number_format($loan->amount, 2) }}</td>
                                            <td>{{ number_format($loan->monthly_repayment, 2) }}</td>
                                            <td>{{ number_format($loan->total_repayment, 2) }}</td>
                                            <td>{{ floor(number_format($loan->total_repayment/$loan->monthly_repayment,1)) }}</td>
                                            <td>{{ $loan->repayment_status }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">Action <i
                                                            class="mdi mdi-chevron-down"></i></button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item btn btn-primary waves-effect waves-light w-sm mr-2"
                                                            href="{{route('loan.edit',['id'=>$loan->id])}}">Edit</a>
                                                        <a class="dropdown-item" href="{{route('loan.view_repayment',['id'=>$loan->id])}}">View Repayment</a>
                                                        <a class="dropdown-item" href="#" data-loan-id="{{$loan->id}}" id="top-up">Top-up Loan</a>
                                                        @if ($loan->repayment_status == 'active')
                                                            <a class="dropdown-item" href="{{route('loan.deactivate',['id'=>$loan->id])}}">Deactivate</a>
                                                        @else
                                                            <a class="dropdown-item" href="{{route('loan.activate',['id'=>$loan->id])}}">Activate</a>
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
        </div> <!-- container-fluid -->
    </div>
    {{-- modal form when top-up is clicked --}}
    <div class="modal fade" id="top-up-modal" tabindex="-1" role="dialog" aria-labelledby="top-up-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <form action="{{ route('loan.top_up') }}" method="post">
                    @csrf
                    <input type="hidden" id="loanId" name="loan_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="top-up-modalLabel">Top-up Loan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label for="horizontal-amount-input" class="col-sm-3 col-form-label">Loan Amount</label>
                            <div class="col-sm-9">
                                <input type="number" name="amount" class="form-control" id="horizontal-amount-input"
                                    placeholder="120000" >
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-ramount-input" class="col-sm-3 col-form-label">Repayment
                                Amount</label>
                            <div class="col-sm-9">
                                <input type="number" name="repayment_amount" class="form-control"
                                    id="horizontal-ramount-input" placeholder="10000">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-prev-amount-input" class="col-sm-3 col-form-label">Previous Repayment
                                Amount</label>
                            <div class="col-sm-9">
                                <input type="number" name="previous_payment" class="form-control"
                                    id="horizontal-prev-amount-input" placeholder="10000">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-interest-input" class="col-sm-3 col-form-label">Interest %</label>
                            <div class="col-sm-9">
                                <input type="number" name="interest" class="form-control"
                                    id="horizontal-interest-input" placeholder="10">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-month-select" class="col-sm-3 col-form-label">Start Date</label>
                            <div class="col-sm-9 row">
                                <div class="col-sm-6">
                                    <select name="start_month" id="horizontal-month-select" class="form-control select">
                                        <option value="">Select month</option>
                                        @foreach ($months as $month)
                                            <option value="{{ $month->name }}">{{ $month->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="start_year" class="form-control"
                                        id="horizontal-year-input" placeholder="Year eg:2023">
                                </div>

                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-duration-input" class="col-sm-3 col-form-label">Duration <small>(No
                                    of Months)</small></label>
                            <div class="col-sm-9">
                                <input type="number" name="duration" class="form-control"
                                    id="horizontal-duration-input" placeholder="12">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
        </div>
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

    <script>
        $(document).ready(function() {
            $('#top-up').click(function() {
                var loanId = $(this).data('loanId');
                $('#loanId').val(loanId);

                $('#top-up-modal').modal('show');
            });
        });

    </script>
@endsection
