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

                        <a href="{{ route('contribution.index') }}"> <button type="submit"
                                class="btn btn-success mr-2">View
                                Contribution List</button></a>


                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-xl-8" style="margin: auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">{{ $pageTitle ?? '' }}</h4>

                            @if (isset($contribution))
                            <form action="{{ route('contribution.update', $contribution->id) }}" method="POST">
                            @else
                            <form action="{{ route('contribution.store') }}" method="POST">
                            @endif
                                    @csrf

                                <div class="row mb-4">
                                    <label for="horizontal-member-select" class="col-sm-3 col-form-label">Member</label>
                                    <div class="col-sm-9">
                                        <div class="mb-3 ajax-select mt-3 mt-lg-0">
                                            <select name="member" id="horizontal-member-select"
                                                class="form-control select2-ajax" {{ isset($contribution) ? 'readonly':'' }}>

                                                @if (isset($contribution))
                                                    <option value="{{ $contribution->member_id ?? '' }}" selected>
                                                        {{ $contribution->member->name ?? '' }}
                                                    </option>
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-prev-input" class="col-sm-3 col-form-label">Previous Contribution Total</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="previous_balance" class="form-control"
                                            id="horizontal-prev-input" placeholder="1000" value="{{$contribution->previous_balance ?? 0}}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="prev-months" class="col-sm-3 col-form-label">No of Months Previously Contributed</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="previous_months" class="form-control"
                                            id="prev-months" placeholder="5" value="{{$contribution->previous_months_no ?? 0}}">
                                    </div>

                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-amount-input" class="col-sm-3 col-form-label">Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="amount" class="form-control"
                                            id="horizontal-amount-input" placeholder="1000" value="{{$contribution->amount ?? 0}}" >
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-9">

                                        <div>
                                            <button type="submit" name="submit"
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

        //search member via ajax request
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
                        t.find(".select2-result-repository__title").text(e.name+" - "+e.account_number)
                        
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
    </script>

    <script>

        // !(function(s) {
        //     "use strict";

        //     function e() {}
        //     (e.prototype.init = function() {
        //         s(".select2").select2(),
        //             s(".select2-limiting").select2({
        //                 maximumSelectionLength: 2
        //             }),
        //             s(".select2-search-disable").select2({
        //                 minimumResultsForSearch: 1 / 0
        //             }),
        //             s(".select2-ajax").select2({
        //                 ajax: {
        //                     url: "{{ route('search-member') }}",
        //                     dataType: "json",
        //                     delay: 250,
        //                     data: function(e) {
        //                         return {
        //                             q: e.term,
        //                             page: e.page,
        //                             _token: "{{ csrf_token() }}",
        //                         };
        //                     },
        //                     processResults: function(e) {
        //                         console.log(e);
        //                         return (
        //                             (e.page = e.page || 1), {
        //                                 results: e.items,
        //                                 pagination: {
        //                                     more: 30 * e.page < e.total_count
        //                                 },
        //                             }
        //                         );
        //                     },
        //                     cache: !0,
        //                 },
        //                 placeholder: "Search for a member",
        //                 minimumInputLength: 1,
        //                 templateResult: function(e) {
        //                     if (e.loading) return e.text;
        //                     var d = s(
        //                         "<div class='select2-result-repository clearfix'><div class='select2-result-repository__meta'><div class='select2-result-repository__title'></div><div class='select2-result-repository__description'></div><div class='select2-result-repository__statistics'></div></div></div>"
        //                     );
        //                     return (
        //                         d.find(".select2-result-repository__title").text(e.full_name),
        //                         d
        //                         .find(".select2-result-repository__description")
        //                         .text(e.description),
        //                         d
        //                     );
        //                 },
        //                 templateSelection: function(e) {
        //                     return e.full_name || e.text;
        //                 },
        //             }),
        //             s(".select2-templating").select2({
        //                 templateResult: function(e) {
        //                     return e.id ?
        //                         s(
        //                             '<span><img src="assets/images/flags/select2/' +
        //                             e.element.value.toLowerCase() +
        //                             '.png" class="img-flag" /> ' +
        //                             e.text +
        //                             "</span>"
        //                         ) :
        //                         e.text;
        //                 },
        //             }),
        //             s("#colorpicker-default").spectrum(),
        //             s("#colorpicker-showalpha").spectrum({
        //                 showAlpha: !0
        //             }),
        //             s("#colorpicker-showpaletteonly").spectrum({
        //                 showPaletteOnly: !0,
        //                 showPalette: !0,
        //                 color: "#34c38f",
        //                 palette: [
        //                     ["#556ee6", "white", "#34c38f", "rgb(255, 128, 0);", "#50a5f1"],
        //                     ["red", "yellow", "green", "blue", "violet"],
        //                 ],
        //             }),
        //             s("#colorpicker-togglepaletteonly").spectrum({
        //                 showPaletteOnly: !0,
        //                 togglePaletteOnly: !0,
        //                 togglePaletteMoreText: "more",
        //                 togglePaletteLessText: "less",
        //                 color: "#556ee6",
        //                 palette: [
        //                     ["#000", "#444", "#666", "#999", "#ccc", "#eee", "#f3f3f3", "#fff"],
        //                     ["#f00", "#f90", "#ff0", "#0f0", "#0ff", "#00f", "#90f", "#f0f"],
        //                     [
        //                         "#f4cccc",
        //                         "#fce5cd",
        //                         "#fff2cc",
        //                         "#d9ead3",
        //                         "#d0e0e3",
        //                         "#cfe2f3",
        //                         "#d9d2e9",
        //                         "#ead1dc",
        //                     ],
        //                     [
        //                         "#ea9999",
        //                         "#f9cb9c",
        //                         "#ffe599",
        //                         "#b6d7a8",
        //                         "#a2c4c9",
        //                         "#9fc5e8",
        //                         "#b4a7d6",
        //                         "#d5a6bd",
        //                     ],
        //                     [
        //                         "#e06666",
        //                         "#f6b26b",
        //                         "#ffd966",
        //                         "#93c47d",
        //                         "#76a5af",
        //                         "#6fa8dc",
        //                         "#8e7cc3",
        //                         "#c27ba0",
        //                     ],
        //                     [
        //                         "#c00",
        //                         "#e69138",
        //                         "#f1c232",
        //                         "#6aa84f",
        //                         "#45818e",
        //                         "#3d85c6",
        //                         "#674ea7",
        //                         "#a64d79",
        //                     ],
        //                     [
        //                         "#900",
        //                         "#b45f06",
        //                         "#bf9000",
        //                         "#38761d",
        //                         "#134f5c",
        //                         "#0b5394",
        //                         "#351c75",
        //                         "#741b47",
        //                     ],
        //                     [
        //                         "#600",
        //                         "#783f04",
        //                         "#7f6000",
        //                         "#274e13",
        //                         "#0c343d",
        //                         "#073763",
        //                         "#20124d",
        //                         "#4c1130",
        //                     ],
        //                 ],
        //             }),
        //             s("#colorpicker-showintial").spectrum({
        //                 showInitial: !0
        //             }),
        //             s("#colorpicker-showinput-intial").spectrum({
        //                 showInitial: !0,
        //                 showInput: !0,
        //             }),
        //             s("#timepicker").timepicker({
        //                 icons: {
        //                     up: "mdi mdi-chevron-up",
        //                     down: "mdi mdi-chevron-down"
        //                 },
        //                 appendWidgetTo: "#timepicker-input-group1",
        //             }),
        //             s("#timepicker2").timepicker({
        //                 showMeridian: !1,
        //                 icons: {
        //                     up: "mdi mdi-chevron-up",
        //                     down: "mdi mdi-chevron-down"
        //                 },
        //                 appendWidgetTo: "#timepicker-input-group2",
        //             }),
        //             s("#timepicker3").timepicker({
        //                 minuteStep: 15,
        //                 icons: {
        //                     up: "mdi mdi-chevron-up",
        //                     down: "mdi mdi-chevron-down"
        //                 },
        //                 appendWidgetTo: "#timepicker-input-group3",
        //             });
        //         var i = {};
        //         s('[data-toggle="touchspin"]').each(function(e, t) {
        //                 var a = s.extend({}, i, s(t).data());
        //                 s(t).TouchSpin(a);
        //             }),
        //             s("input[name='demo3_21']").TouchSpin({
        //                 initval: 40,
        //                 buttondown_class: "btn btn-primary",
        //                 buttonup_class: "btn btn-primary",
        //             }),
        //             s("input[name='demo3_22']").TouchSpin({
        //                 initval: 40,
        //                 buttondown_class: "btn btn-primary",
        //                 buttonup_class: "btn btn-primary",
        //             }),
        //             s("input[name='demo_vertical']").TouchSpin({
        //                 verticalbuttons: !0
        //             }),
        //             s("input#defaultconfig").maxlength({
        //                 warningClass: "badge bg-info",
        //                 limitReachedClass: "badge bg-warning",
        //             }),
        //             s("input#thresholdconfig").maxlength({
        //                 threshold: 20,
        //                 warningClass: "badge bg-info",
        //                 limitReachedClass: "badge bg-warning",
        //             }),
        //             s("input#moreoptions").maxlength({
        //                 alwaysShow: !0,
        //                 warningClass: "badge bg-success",
        //                 limitReachedClass: "badge bg-danger",
        //             }),
        //             s("input#alloptions").maxlength({
        //                 alwaysShow: !0,
        //                 warningClass: "badge bg-success",
        //                 limitReachedClass: "badge bg-danger",
        //                 separator: " out of ",
        //                 preText: "You typed ",
        //                 postText: " chars available.",
        //                 validate: !0,
        //             }),
        //             s("textarea#textarea").maxlength({
        //                 alwaysShow: !0,
        //                 warningClass: "badge bg-info",
        //                 limitReachedClass: "badge bg-warning",
        //             }),
        //             s("input#placement").maxlength({
        //                 alwaysShow: !0,
        //                 placement: "top-left",
        //                 warningClass: "badge bg-info",
        //                 limitReachedClass: "badge bg-warning",
        //             });
        //     }),
        //     (s.AdvancedForm = new e()),
        //     (s.AdvancedForm.Constructor = e);
        // })(window.jQuery),
        // (function() {
        //     "use strict";
        //     window.jQuery.AdvancedForm.init();
        // })(),
        // $(function() {
        //     "use strict";
        //     var o = $(".docs-date"),
        //         n = $(".docs-datepicker-container"),
        //         r = $(".docs-datepicker-trigger"),
        //         l = {
        //             show: function(e) {
        //                 console.log(e.type, e.namespace);
        //             },
        //             hide: function(e) {
        //                 console.log(e.type, e.namespace);
        //             },
        //             pick: function(e) {
        //                 console.log(e.type, e.namespace, e.view);
        //             },
        //         };
        //     o
        //         .on({
        //             "show.datepicker": function(e) {
        //                 console.log(e.type, e.namespace);
        //             },
        //             "hide.datepicker": function(e) {
        //                 console.log(e.type, e.namespace);
        //             },
        //             "pick.datepicker": function(e) {
        //                 console.log(e.type, e.namespace, e.view);
        //             },
        //         })
        //         .datepicker(l),
        //         $(".docs-options, .docs-toggles").on("change", function(e) {
        //             var t,
        //                 a = e.target,
        //                 i = $(a),
        //                 s = i.attr("name"),
        //                 c = "checkbox" === a.type ? a.checked : i.val();
        //             switch (s) {
        //                 case "container":
        //                     c ? (c = n).show() : n.hide();
        //                     break;
        //                 case "trigger":
        //                     c ? (c = r).prop("disabled", !1) : r.prop("disabled", !0);
        //                     break;
        //                 case "inline":
        //                     (t = $('input[name="container"]')).prop("checked") || t.click();
        //                     break;
        //                 case "language":
        //                     $('input[name="format"]').val($.fn.datepicker.languages[c].format);
        //             }
        //             (l[s] = c), o.datepicker("reset").datepicker("destroy").datepicker(l);
        //         }),
        //         $(".docs-actions").on("click", "button", function(e) {
        //             var t,
        //                 a = $(this).data(),
        //                 i = a.arguments || [];
        //             e.stopPropagation(),
        //                 a.method &&
        //                 (a.source ?
        //                     o.datepicker(a.method, $(a.source).val()) :
        //                     (t = o.datepicker(a.method, i[0], i[1], i[2])) &&
        //                     a.target &&
        //                     $(a.target).val(t));
        //         });
        // });
    </script>
@endsection
