<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $pageTitle = "Dashboard";
        //total members
        $totalMembers = \App\Models\Member::count();
        //total active members
        $totalActiveMembers = \App\Models\Member::where('status', 1)->count();
        //total inactive members
        $totalInactiveMembers = \App\Models\Member::where('status', 0)->count();
        //total monthly contributions
        $totalMonthlyContributions = \App\Models\MonthlyContribution::sum('total_amount');
        //total loan amount
        $totalLoanAmount = \App\Models\Loan::sum('amount');

        $totalContributions = \App\Models\Contribution::sum('balance');

        return view('dashboard', compact('pageTitle', 'totalMembers', 'totalActiveMembers', 'totalInactiveMembers', 'totalMonthlyContributions', 'totalLoanAmount', 'totalContributions'));
    }

}
