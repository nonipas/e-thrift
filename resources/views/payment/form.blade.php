@extends('layouts.app')
@section('title', $pageTitle)

@section('meta')

@endsection

@section('link')
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet') }}" type="text/css">
    <link href="{{ asset('assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/libs/%40chenfengyuan/datepicker/datepicker.min.css') }}">
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }}</h4>

                        <a href="{{ route('payment.batch') }}"> <button type="submit"
                                class="btn btn-success mr-2">View
                                Payment List</button></a>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-xl-8" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $batch_name }}</h4>

                            <form action="{{route('payment.store')}}" method="post">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-batch" class="col-sm-3 col-form-label">Batch Name</label>
                                    <div class="col-sm-9">
                                        @if ($batch_id == 0)
                                            
                                            <select name="batch" id="horizontal-batch" class="form-control select2">
                                                <option>Select</option>
                                                <option value="1">UW_COOP_BATCH_PAYMENT_1</option>
                                                @foreach ($batches as $batch)
                                                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" name="batch_name" class="form-control" id="horizontal-batch" value="{{$batch_name}}" readonly>
                                            <input type="hidden" id="batch-id" name="batch" value="{{$batch_id}}">
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-category-select" class="col-sm-3 col-form-label">Category</label>
                                    <div class="col-sm-9">
                                            <select name="category" id="horizontal-type-select"
                                                class="form-control select2">
                                                <option>Select</option>
                                                @foreach ($payment_categories as $category)
                                                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <div id="external">
                                    <div class="row mb-4">
                                        <label for="horizontal-account-input" class="col-sm-3 col-form-label">Account No</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="account_no" class="form-control"
                                                id="horizontal-account-input" placeholder="0123456789 ">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-bank-select" class="col-sm-3 col-form-label">Bank</label>
                                        <div class="col-sm-9">
                                            <select name="bank" id="horizontal-bank-select" class="form-control select2">
                                                <option>Select</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->code }}">{{ $bank->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-4">
                                        <label for="horizontal-name-input" class="col-sm-3 col-form-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="name" class="form-control" id="horizontal-name-input"
                                                placeholder="" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="horizontal-amount-input" class="col-sm-3 col-form-label">Amount</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="amount" class="form-control"
                                                id="horizontal-amount-input" placeholder="120000">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-narration-input" class="col-sm-3 col-form-label">Narration</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="description" class="form-control"
                                            id="horizontal-narration-input" placeholder="UW-COOP-Loan-Sept, 2023">
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <button type="submit" name="submit"
                                                    class="btn btn-primary w-md">Submit</button>
                                            </div>
                                            <div class="col-sm-9">
                                                <div id="loan" class="d-none">
                                                    <button type="button" id="add-loan" name="add-loan"
                                                        class="btn btn-warning w-md">Add System Loan</button>
                                                </div>
                                                <div id="dividend" class="d-none">
                                                    <button type="button" id="add-dividend" name="add-dividend"
                                                        class="btn btn-warning w-md">Add System Dividend</button>
                                                </div>
                                            </div>
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
            <!-- end row -->

            <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container-fluid -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/libs/%40chenfengyuan/datepicker/datepicker.min.js') }}"></script>

    <!-- form advanced init -->
    {{-- <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script> --}}
    <script>
        //function to show either add loan or add dividend button when category is selected
        $(document).ready(function() {
            $('#horizontal-type-select').on('change', function() {
                var category = $(this).val();
                if (category == 'loan') {
                    $('#loan').removeClass('d-none');
                    $('#dividend').addClass('d-none');
                } else if (category == 'dividend') {
                    $('#dividend').removeClass('d-none');
                    $('#loan').addClass('d-none');
                } else {
                    $('#loan').addClass('d-none');
                    $('#dividend').addClass('d-none');
                }
            });
        });

        //function to get account name when bank is selected
        $(document).ready(function() {
            $('#horizontal-bank-select').on('change', function() {
                var bank = $('#horizontal-bank-select').val();
            var account_number = $('#horizontal-account-input').val();
            if (bank != '' && account_number != '') {
                $.ajax({
                    url: "{{ route('get-account-name') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "bank": bank,
                        "account_number": account_number
                    },
                    success: function(response) {
                        if (response.status == true) {
                            $('#horizontal-name-input').attr('readonly', 'readonly');
                            $('#horizontal-name-input').val(response.account_name);
                        } else {
                            $('#horizontal-name-input').val('');
                            $('#horizontal-name-input').removeAttr('readonly');
                        }
                        
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
            });
        });

        //function to add loan from system but it should be confirmed by the user
        $(document).ready(function() {
            $('#add-loan').on('click', function() {
               $('#preloader').show();
                setTimeout(function() {
                    $('#preloader').hide();
                     addSystemPayment();
                }, 3000);
            });

            $('#add-dividend').on('click', function() {
                $('#preloader').show();
                 setTimeout(function() {
                      $('#preloader').hide();
                        addSystemPayment();
                 }, 3000);
            });
        });

        function addSystemPayment(){
            var narration = $('#horizontal-narration-input').val();
            var batch = $('#batch-id').val();
            var category = $('#horizontal-type-select').val();
            if (batch != '' && category != '') {
                
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to add "+category+" from system!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#6259ca',
                        cancelButtonColor: '#6259ca',
                        confirmButtonText: 'Yes, Continue!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('payment.add_system_payment') }}",
                                type: "POST",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "batch": batch,
                                    "category": category,
                                    "narration": narration
                                },
                                success: function(response) {
                                    if (response.status == 'success') {
                                        Swal.fire(
                                            'Success!',
                                            response.message,
                                            'success'
                                        ).then((result) => {
                                            //load view batch page
                                            var url = "{{ route('payment.view_batch','') }}"+"/"+batch;
                                            window.location.href = url;
                                        })
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            response.message,
                                            'error'
                                        )
                                    }
                                    
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        }
                    })
            }
        }
      
    </script>
@endsection
