@extends('layouts.app')
@section('title', $pageTitle)

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
                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                            <li class="breadcrumb-item active">home</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->



        <div class="row">
            <div class="col-xl-12">
                <div class="card bg-primary bg-soft">
                    <div>
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-3">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p>E-thrift Admin Dashboard</p>

                                    <ul class="ps-3 mb-0">
                                        <li class="py-2"><i class="bx bxs-bank mr-2"></i> Loan Management</li>
                                        <li class="py-2"><i class="bx bx-layer mr-2"></i> Contribution Management</li>
                                        <li class="py-2"><i class="bx bx-calculator mr-2"></i> Dividend Management</li>
                                        <li class="py-2"><i class="bx bx-money"></i> Payment Management</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                            <i class="bx bx-copy-alt"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-14 mb-0">Members</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4>{{number_format($totalActiveMembers??0)}} <i class="mdi mdi-chevron-up ms-1 text-success"></i></h4>
                                    <div class="d-flex">
                                        {{-- <span class="badge badge-soft-success font-size-12"> + 0.2% </span> <span class="ms-2 text-truncate">From previous period</span> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                            <i class="bx bx-archive-in"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-14 mb-0">Total Contributions</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4>N {{number_format($totalContributions,2)}} <i class="mdi mdi-chevron-up ms-1 text-success"></i></h4>
                                    <div class="d-flex">
                                        {{-- <span class="badge badge-soft-success font-size-12"> + 0.2% </span> <span class="ms-2 text-truncate">From previous period</span> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                            <i class="bx bx-purchase-tag-alt"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-14 mb-0">Total Loan</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4>N {{number_format($totalLoanAmount,2)}} <i class="mdi mdi-chevron-up ms-1 text-success"></i></h4>
                                    
                                    <div class="d-flex">
                                        {{-- <span class="badge badge-soft-warning font-size-12"> 0% </span> <span class="ms-2 text-truncate">From previous period</span> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>

    </div> <!-- container-fluid -->
</div>

@endsection

@section('script')

@endsection