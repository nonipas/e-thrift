@extends('layouts.app')
@section('meta')

@endsection

@section('link')

@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{ $pageTitle ?? '' }}</h4>


                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">

            <div class="col-xl-8" style="margin: auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ $pageTitle ?? '' }}</h4>

                        @if($payment_category == null)
                        <form action="{{ route('setting.payment_category_store') }}" method="post">
                        @else
                        <form action="{{ route('setting.payment_category_update', $payment_category->id) }}" method="post">
                        @endif
                        @csrf

                            <div class="row mb-4">
                                <label for="horizontal-name-input" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-5">
                                    <input type="text" name="name" class="form-control"
                                        id="horizontal-name-input" placeholder="loan" value="{{ $payment_category->name ?? '' }}">
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-11">

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
        {{-- table starts --}}
        <div class="row">
            <div class="col-xl-8" style="margin: auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $pageTitle ?? '' }} List</h4>
                        
                        <table id="datatable-buttons" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- loop through payment categories --}}
                                @foreach ($payment_categories as $payment_category)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$payment_category->name}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">Action <i
                                                    class="mdi mdi-chevron-down"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item btn btn-primary waves-effect waves-light w-sm mr-2"
                                                    href="{{ route('setting.payment_category').'?id='.$payment_category->id }}" id="edit" >Edit</a>
                                                <a class="dropdown-item" id="delete" href="{{ route('setting.payment_category_delete', $payment_category->id) }}">Delete</a>
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

        <!-- end col -->
    </div>
    <!-- end row -->

</div> <!-- container-fluid -->
</div>
@endsection
@section('script')

@endsection
