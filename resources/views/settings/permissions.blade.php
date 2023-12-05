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

                        @if($permission == null)
                        <form action="{{ route('setting.permission_store') }}" method="post">
                        @else
                        <form action="{{ route('setting.permission_update', $permission->id) }}" method="post">
                        @endif
                            @csrf
                            <div class="row mb-4">
                                <label for="horizontal-name-input1" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-5">
                                    <input type="text" name="name" class="form-control"
                                        id="horizontal-name-input1" placeholder="Add user" value=" {{$permission->name ?? '' }}" >
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="description" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-sm-5">
                                    <textarea name="description" class="form-control" id="description"
                                        placeholder="Description">{{ $permission->description ?? '' }}</textarea>
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
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->description}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">Action <i
                                                    class="mdi mdi-chevron-down"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item btn btn-primary waves-effect waves-light w-sm mr-2"
                                                    href="{{ route('setting.permission').'?id='.$permission->id }}">Edit</a>
                                                <a class="dropdown-item" href="{{ route('setting.permission_delete', $permission->id) }}">Delete</a>
                                            </div>
                                        </div>
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
