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

                        <a href="{{ route('loan.index') }}"> <button type="submit" class="btn btn-success mr-2">View
                                Loan List</button></a>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-xl-8" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $pageTitle ?? '' }}</h4>

                            @if (isset($loan))
                                <form action="{{ route('loan.update', $loan->id) }}" method="POST">
                                @else
                                    <form action="{{ route('loan.store') }}" method="POST">
                            @endif
                            @csrf

                            <div class="row mb-4">
                                <label for="horizontal-member-select" class="col-sm-3 col-form-label">Member</label>
                                <div class="col-sm-9">
                                    <div class="mb-3 ajax-select mt-3 mt-lg-0">
                                        <select name="member" id="horizontal-member-select" onchange="getMemberDetail()"
                                            class="form-control select2-ajax" {{ isset($loan) ? 'readonly' : '' }} >

                                            @if (isset($loan))
                                                <option value="{{ $loan->member_id ?? '' }}" selected>
                                                    {{ $loan->member->name ?? '' }}
                                                </option>
                                            @endif

                                        </select>
                                        <button type="button" name="reset"
                                        class="btn btn-warning w-md mt-2" onclick="clearMemberInput()">Clear</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-account-input" class="col-sm-3 col-form-label">Account No</label>
                                <div class="col-sm-9">
                                    <input type="text" name="account_no" class="form-control"
                                        id="horizontal-account-input" placeholder="0123456789 "
                                        value="{{ $loan->beneficiary_account_no ?? '' }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-bank-select" class="col-sm-3 col-form-label">Bank</label>
                                <div class="col-sm-9">
                                    <select name="bank" id="horizontal-bank-select" class="form-control select2">
                                        <option value="">Select</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->code }}"
                                                {{ isset($loan) && $loan->beneficiary_bank == $bank->code ? 'selected' : '' }}>
                                                {{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-name-input" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" id="horizontal-name-input"
                                        placeholder="" value="{{ $loan->beneficiary_name ?? '' }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="horizontal-amount-input" class="col-sm-3 col-form-label">Loan Amount</label>
                                <div class="col-sm-9">
                                    <input type="number" name="amount" class="form-control" id="horizontal-amount-input"
                                        placeholder="120000" value="{{ $loan->amount ?? '' }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-ramount-input" class="col-sm-3 col-form-label">Repayment
                                    Amount</label>
                                <div class="col-sm-9">
                                    <input type="number" name="repayment_amount" class="form-control"
                                        id="horizontal-ramount-input" placeholder="10000"
                                        value="{{ $loan->monthly_repayment ?? '' }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-interest-input" class="col-sm-3 col-form-label">Interest %</label>
                                <div class="col-sm-9">
                                    <input type="number" name="interest" class="form-control"
                                        id="horizontal-interest-input" placeholder="10"
                                        value="{{ $loan->interest ?? '' }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-month-select" class="col-sm-3 col-form-label">Start Date</label>
                                <div class="col-sm-9 row">
                                    <div class="col-sm-6">
                                        <select name="start_month" id="horizontal-month-select" class="form-control select">
                                            <option value="">Select month</option>
                                            @foreach ($months as $month)
                                                <option value="{{ $month->name }}"
                                                    {{ isset($loan) && $loan->repayment_start_month == $month->name ? 'selected' : '' }}>
                                                    {{ $month->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="start_year" class="form-control"
                                            id="horizontal-year-input" placeholder="Year eg:2023"
                                            value="{{ $loan->repayment_start_year ?? '' }}">
                                    </div>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-duration-input" class="col-sm-3 col-form-label">Duration <small>(No
                                        of Months)</small></label>
                                <div class="col-sm-9">
                                    <input type="number" name="duration" class="form-control"
                                        id="horizontal-duration-input" placeholder="12"
                                        value="{{ $loan->duration ?? '' }}">
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <div>
                                        <button type="submit" name="add-admin"
                                            class="btn btn-primary w-md">Submit</button>
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
        !(function(s) {
            "use strict";

            function e() {}
            (e.prototype.init = function() {
                s(".select2").select2(),
                    s(".select2-limiting").select2({
                        maximumSelectionLength: 2
                    }),
                    s(".select2-search-disable").select2({
                        minimumResultsForSearch: 1 / 0
                    }),
                    s(".select2-ajax").select2({
                        ajax: {
                            url: "{{ route('search-member') }}",
                            dataType: "json",
                            delay: 250,
                            data: function(e) {
                                return {
                                    q: e.term,
                                    page: e.page,
                                    _token: "{{ csrf_token() }}",
                                };
                            },
                            processResults: function(data, t) {
                                console.log(data);
                                return {
                                    results: data.items,
                                    pagination: {
                                        more: 30 * t.page < data.total_count
                                    },
                                };
                            },
                            cache: true
                        },

                        minimumInputLength: 1,
                        templateResult: function(e) {
                            if (e.loading) return e.text;
                            var t = s(
                                "<div class='select2-result-repository clearfix'><div class='select2-result-repository__meta'><div class='select2-result-repository__title'></div></div></div>"
                            );
                            return (
                                t.find(".select2-result-repository__title").text(e.name + " - " + e
                                    .account_number)

                            );
                        },
                        templateSelection: function(e) {
                            return e.name || e.text;
                        },
                    });
            }),
            (s.FormAdvanced = new e()),
            (s.FormAdvanced.Constructor = e);
        })(window.jQuery),
        (function() {
            "use strict";
            window.jQuery.FormAdvanced.init();
        })();

        function getAccountName() {
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
        }

        function getMemberDetail() {
            var member = $('#horizontal-member-select').val();
            var account_number = $('#horizontal-account-input');
            var name = $('#horizontal-name-input');
            var bank = $('#horizontal-bank-select');
            if ($.isNumeric(member)) {
                $.ajax({
                    url: "{{ route('get-member') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": member,
                    },
                    success: function(response) {
                        if (response.status == true) {
                            name.attr('readonly', 'readonly');
                            account_number.attr('readonly', 'readonly');
                            bank.attr('readonly', 'readonly');
                            name.val(response.name);
                            account_number.val(response.account_number);
                            bank.val(response.bank).change();
                        } else {
                            name.val('');
                            account_number.val('');
                            bank.val('').change();
                            name.removeAttr('readonly');
                            account_number.removeAttr('readonly');
                            bank.removeAttr('readonly');
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
        }

        function clearMemberInput(){
            var account_number = $('#horizontal-account-input');
            var name = $('#horizontal-name-input');
            var bank = $('#horizontal-bank-select');
            $('#horizontal-member-select').val('').change();
            name.val('');
            account_number.val('');
            bank.val('').change();
            name.removeAttr('readonly');
            account_number.removeAttr('readonly');
            bank.removeAttr('readonly');
        }
    </script>
@endsection
