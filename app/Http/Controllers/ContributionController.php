<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helpers;
use App\Models\Contribution;
use App\Models\MonthlyContribution;
use App\Models\MonthlyContributionDetail;
use App\Models\Member;
use App\Models\User;

class ContributionController extends Controller
{
    //store contribution
    public function store(Request $request)
    {

        //validate request using validate function in helpers class
        $validate = Helpers::validateRequest($request, [
            'member' => 'required',
            'amount' => 'required|numeric',
            'previous_balance' => 'numeric'
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //check if member exists
        $member = Member::find($request->member);
        if(!$member){
            toastr()->error('Member not found');
            return redirect()->back();
        }

        //check if member has a contribution
        $contribution = Contribution::where('member_id', $request->member)->first();
        if($contribution){
            toastr()->error('Member already has a contribution');
            return redirect()->back();
        }

        $contribution = Contribution::create([
            'member_id' => $request->member,
            'amount' => $request->amount,
            'no_of_months' => $request->previous_months,
            'previous_months_no' => $request->previous_months,
            'balance' => $request->previous_balance,
            'previous_balance' => $request->previous_balance,
        ]);

        //store activity from helpers class
        Helpers::storeActivity('added contribution for '.$contribution->member->name.'');

        toastr()->success('Contribution added successfully');
        return redirect()->route('contribution.index');
    }

    //update contribution
    public function update(Request $request, $id)
    {
        //validate request using validate function in helpers
        $validate = Helpers::validateRequest($request, [
            'member' => 'required',
            'amount' => 'required|numeric',
            'previous_balance' => 'numeric'
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //check if member exists
        $member = Member::find($request->member);
        if(!$member){
            toastr()->error('Member not found');
            return redirect()->back();
        }

        //check if member has a contribution
        $contribution = Contribution::where('member_id', $request->member)->first();
        if(!$contribution){
            toastr()->error('Member does not have a contribution');
            return redirect()->back();
        }
        $no_of_months = $contribution->no_of_months;
        //if previous month request is not same as previous month in database, update no of months
        if($request->previous_months != $contribution->previous_months_no){
            $no_of_months = ($contribution->no_of_months + $request->previous_months) - $contribution->previous_months_no;
                
        }
        $contribution->update([
            'member_id' => $request->member,
            'amount' => $request->amount,
            'no_of_months' => $no_of_months,
            'previous_months_no' => $request->previous_months,
            'balance' => $request->previous_balance,
            'previous_balance' => $request->previous_balance,
        ]);

        //store activity from helpers class
        Helpers::storeActivity('updated contribution for '.$contribution->member->name.'');

        toastr()->success('Contribution updated successfully');
        return redirect()->route('contributions.index');
    }

    //delete contribution
    public function destroy($id)
    {
        $contribution = Contribution::find($id);
        if(!$contribution){
            toastr()->error('Contribution not found');
            return redirect()->back();
        }
        $contribution->delete();

        //store activity from helpers class
        Helpers::storeActivity('deleted contribution for '.$contribution->member->name.'');

        toastr()->success('Contribution deleted successfully');
        return redirect()->route('contribution.index');
    }

    //change contribution status
    public function changeStatus(Request $request, $id)
    {
        $contribution = Contribution::find($id);
        if(!$contribution){
            toastr()->error('Contribution not found');
            return redirect()->back();
        }
        $contribution->update([
            'status' => !$contribution->status
        ]);

        //store activity from helpers class
        Helpers::storeActivity('Changed contribution status for '.$contribution->member->name.'');

        toastr()->success('Contribution status changed successfully');
        return redirect()->route('contribution.index');
    }

    //generate monthly contribution
    public function generateMonthlyContribution(Request $request)
    {
        //validate request using validate function in helpers
        $validate = Helpers::validateRequest($request, [
            'month' => 'required',
            'year' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //check if monthly contribution exists and is approved
        $monthly_contribution = MonthlyContribution::where('month', $request->month)->where('year', $request->year)->first();
        if($monthly_contribution && $monthly_contribution->is_approved){
            toastr()->error('Monthly contribution already exists');
            return redirect()->back();
        }

        //get all active contributions
        $contributions = Contribution::all();

        //create monthly contribution
        $monthly_contribution = MonthlyContribution::create([
            'month' => $request->month,
            'year' => $request->year,
        ]);

        //loop through members and create monthly contribution details
        foreach($contributions as $contribution){

            //check if approved monthly contribution detail exists for the member and month, then skip
            $monthly_contribution_detail = MonthlyContributionDetail::where('member_id', $contribution->member_id)->where('month', $request->month)->where('year', $request->year)->where('is_approved',1)->first();
            if($monthly_contribution_detail){
                continue;
            }

            //check if member has a contribution and also if the member is active
            if(!$contribution || !$contribution->member->status){
                continue;
            }

            //create monthly contribution detail

            MonthlyContributionDetail::create([
                'monthly_contribution_id' => $monthly_contribution->id,
                'member_id' => $contribution->member_id,
                'amount' => $contribution->amount,
                'month' => $request->month,
                'year' => $request->year,
            ]);
        }

        //get total amount contributed and update monthly contribution
        $total_amount = MonthlyContributionDetail::where('monthly_contribution_id', $monthly_contribution->id)->sum('amount');
        $monthly_contribution->update([
            'total_amount' => $total_amount
        ]);

        //store activity from helpers class
        Helpers::storeActivity('generated monthly contribution for '.$request->month.' '.$request->year.'');

        toastr()->success('Monthly contribution generated successfully');
        return redirect()->route('contribution.generate');
    }

    //approve monthly contribution
    public function approveMonthlyContribution(Request $request)
    {
        $monthly_contribution = MonthlyContribution::where('month', $request->month)->where('year', $request->year)->first();

        if(!$monthly_contribution){
            //return json message
            return response()->json([
                'status'=>'failed',
                'message'=> 'no approvable monthly contribution found'
            ],404);
        }

        //check if monthly contribution has been approved
        if($monthly_contribution->is_approved){
            toastr()->error('Monthly contribution has already been approved');
            return response()->json([
                'status'=>'failed',
                'message'=>'Monthly contribution has already been approved'
            ]);
        }

        //get all monthly contribution details
        $monthly_contribution_details = MonthlyContributionDetail::where('monthly_contribution_id', $monthly_contribution->id)->get();

        //loop through monthly contribution details and update
        foreach($monthly_contribution_details as $monthly_contribution_detail){
            $monthly_contribution_detail->update([
                'is_approved' => 1,
                'approved_by' => auth()->user()->id??0,
                'approved_at' => now(),
            ]);

            //update contribution balance and no of months
            $contribution = Contribution::where('member_id', $monthly_contribution_detail->member_id)->first();
            $contribution->update([
                'balance' => $contribution->balance + $monthly_contribution_detail->amount,
                'no_of_months' => $contribution->no_of_months + 1,
            ]);
        }

        //update monthly contribution
        $monthly_contribution->update([
            'is_approved' => 1,
            'approved_by' => auth()->user()->id??0,
            'approved_at' => now(),
        ]);

        //store activity from helpers class
        Helpers::storeActivity('approved monthly contribution for '.$monthly_contribution->month.' '.$monthly_contribution->year.'');

        //put monthly contribution details in a table body
        $table_body = '';
        $i = 1;
        foreach($monthly_contribution_details as $monthly_contribution_detail){

            
            //if the monthly contribution has been approved, disable the approve button and delete button
            $approve_button = '<a href="'.route('contribution.approve_monthly_member', $monthly_contribution_detail->id).'" class="btn btn-success btn-sm">Approve</a>';
            $delete_button = '<a href="'.route('contribution.delete_monthly_detail', $monthly_contribution_detail->id).'" class="btn btn-danger btn-sm">Delete</a>';
            $status = 'unapproved';
            if($monthly_contribution_detail->is_approved){
                $approve_button = '<a href="#" class="btn btn-success btn-sm" disabled>Approve</a>';
                $delete_button = '<a href="#" class="btn btn-danger btn-sm" disabled>Delete</a>';
                $status = 'approved';
            }
            $table_body .= '<tr>
                <td>'.$i++.'</td>
                <td>'.$monthly_contribution_detail->member->name.'</td>
                <td>'.$monthly_contribution_detail->amount.'</td>
                <td>'.$monthly_contribution_detail->month.'</td>
                <td>'.$monthly_contribution_detail->year.'</td>
                <td>'.$status.'</td>
                <td>
                    '.$approve_button.'
                    '.$delete_button.'
                </td>
            </tr>';
        }


        toastr()->success('Monthly contribution approved successfully');
        return response()->json([
            'status'=>'success',
            'message'=>'Monthly contribution approved successfully',
            'data' => $table_body,
        ],200);
    }

    //approve monthly contribution by id
    public function approveMonthlyContributionById(Request $request, $id)
    {
        $monthly_contribution = MonthlyContribution::find($id);
        if(!$monthly_contribution){
            toastr()->error('Monthly contribution not found');
            return redirect()->back();
        }

        //check if monthly contribution has been approved
        if($monthly_contribution->is_approved){
            toastr()->error('Monthly contribution has already been approved');
            return redirect()->back();
        }

        //get all monthly contribution details
        $monthly_contribution_details = MonthlyContributionDetail::where('monthly_contribution_id', $monthly_contribution->id)->get();

        //loop through monthly contribution details and update
        foreach($monthly_contribution_details as $monthly_contribution_detail){
            $monthly_contribution_detail->update([
                'is_approved' => 1,
                'approved_by' => auth()->user()->id??0,
                'approved_at' => now(),
            ]);

            //update contribution balance
            $contribution = Contribution::where('member_id', $monthly_contribution_detail->member_id)->first();
            $contribution->update([
                'balance' => $contribution->balance + $monthly_contribution_detail->amount,
                'no_of_months' => $contribution->no_of_months + 1,
            ]);
        }

        //update monthly contribution
        $monthly_contribution->update([
            'is_approved' => 1,
            'approved_by' => auth()->user()->id??0,
            'approved_at' => now(),
        ]);

        //store activity from helpers class
        Helpers::storeActivity('approved monthly contribution for '.$monthly_contribution->month.' '.$monthly_contribution->year.'');

        toastr()->success('Monthly contribution approved successfully');
        return redirect()->route('contribution.generate');
    }

    //delete unapproved monthly contribution
    public function deleteMonthlyContribution(Request $request, $id)
    {
        $monthly_contribution = MonthlyContribution::find($id);
        if(!$monthly_contribution){
            toastr()->error('Monthly contribution not found');
            return redirect()->back();
        }

        //check if monthly contribution has been approved
        // if($monthly_contribution->is_approved){
        //     toastr()->error('Monthly contribution has already been approved');
        //     return redirect()->back();
        // }

        //get all monthly contribution details
        $monthly_contribution_details = MonthlyContributionDetail::where('monthly_contribution_id', $monthly_contribution->id)->get();

        //loop through monthly contribution details and delete
        foreach($monthly_contribution_details as $monthly_contribution_detail){
            
            
            //update member balance if monthly contribution has been approved
            if($monthly_contribution_detail->is_approved){
                //update contribution balance if monthly contribution has been approved
                $contribution = Contribution::where('member_id', $monthly_contribution_detail->member_id)->first();
                $contribution->update([
                    'balance' => $contribution->balance - $monthly_contribution_detail->amount,
                    'no_of_months' => $contribution->no_of_months - 1,
                ]);
            }

            $monthly_contribution_detail->delete();

        }

        //delete monthly contribution
        $monthly_contribution->delete();

        //store activity from helpers class
        Helpers::storeActivity('deleted monthly contribution for '.$monthly_contribution->month.' '.$monthly_contribution->year.'');

        toastr()->success('Monthly contribution deleted successfully');
        return redirect()->route('contribution.generate');
    }

    //approve monthly contribution for a single member
    public function approveMonthlyContributionForMember(Request $request, $id)
    {
        $monthly_contribution_detail = MonthlyContributionDetail::find($id);
        if(!$monthly_contribution_detail){
            toastr()->error('Monthly contribution not found');
            return redirect()->back();
        }

        //check if monthly contribution has been approved
        if($monthly_contribution_detail->is_approved){
            toastr()->error('Monthly contribution has already been approved');
            return redirect()->back();
        }

        //update monthly contribution
        $monthly_contribution_detail->update([
            'is_approved' => 1,
            'approved_by' => auth()->user()->id??0,
            'approved_at' => now(),
        ]);

        //update contribution balance
        $contribution = Contribution::where('member_id', $monthly_contribution_detail->member_id)->first();
        $contribution->update([
            'balance' => $contribution->balance + $monthly_contribution_detail->amount,
            'no_of_months' => $contribution->no_of_months + 1,
        ]);

        //store activity from helpers class
        Helpers::storeActivity('approved monthly contribution for '.$monthly_contribution_detail->member->name.' '.$monthly_contribution_detail->month.' '.$monthly_contribution_detail->year.'');

        toastr()->success('Monthly contribution approved successfully');
        return redirect()->route('contribution.generate');
    }

    //delete monthly contribution detail
    public function deleteMonthlyContributionDetail(Request $request, $id)
    {
        $monthly_contribution_detail = MonthlyContributionDetail::find($id);
        if(!$monthly_contribution_detail){
            toastr()->error('Monthly contribution not found');
            return redirect()->back();
        }

        //check if monthly contribution has been approved
        if($monthly_contribution_detail->is_approved){
            // toastr()->error('Monthly contribution has already been approved');
            // return redirect()->back();

            //update contribution balance if monthly contribution has been approved
            $contribution = Contribution::where('member_id', $monthly_contribution_detail->member_id)->first();
            $contribution->update([
                'balance' => $contribution->balance - $monthly_contribution_detail->amount,
                'no_of_months' => $contribution->no_of_months - 1,
            ]);
            
        }

        //delete monthly contribution
        $monthly_contribution_detail->delete();

        //store activity from helpers class
        Helpers::storeActivity('deleted monthly contribution for '.$monthly_contribution_detail->member->name.' '.$monthly_contribution_detail->month.' '.$monthly_contribution_detail->year.'');

        toastr()->success('Monthly contribution deleted successfully');
        return redirect()->route('contribution.generate');
    }

    //search monthly contribution
    public function searchMonthlyContribution(Request $request)
    {

        //get monthly contribution
        $monthly_contribution = MonthlyContribution::where('month', $request->month)->where('year', $request->year)->first();

        //if monthly contribution does not exist, return empty table body
        if(!$monthly_contribution){
            return response()->json([
                'data' => '<tr><td valign="top" colspan="7" class="dataTables_empty">No data available in table</td></tr>',
            ]);
        }

        //get monthly contribution details
        $monthly_contribution_details = MonthlyContributionDetail::where('monthly_contribution_id', $monthly_contribution->id)->get();

        if(!$monthly_contribution){
            //get all monthly contributions details for the latest month
            $monthly_contribution = MonthlyContribution::orderBy('id', 'desc')->first();
            $monthly_contribution_details = MonthlyContributionDetail::where('monthly_contribution_id', $monthly_contribution->id)->get();
        }

        //put monthly contribution details in a table body
        $table_body = '';
        $i = 1;
        foreach($monthly_contribution_details as $monthly_contribution_detail){
            $approved_by = User::find($monthly_contribution_detail->approved_by) ?? '';
            $date_approved = $monthly_contribution_detail->approved_at ? date('d M Y H:i:s',strtotime($monthly_contribution_detail->approved_at)):'';
            //if the monthly contribution has been approved, disable the approve button and delete button
            $approve_button = '<a href="'.route('contribution.approve_monthly_member', $monthly_contribution_detail->id).'" class="btn btn-success btn-sm">Approve</a>';
            $delete_button = '<a href="'.route('contribution.delete_monthly_detail', $monthly_contribution_detail->id).'" class="btn btn-danger btn-sm">Delete</a>';

            //table date for button
            $button = '<td>'.$approve_button.$delete_button.'</td>';

            $status = 'unapproved';
            if($monthly_contribution_detail->is_approved){
                $approve_button = '<a href="#" class="btn btn-success btn-sm" disabled>Approve</a>';
                $delete_button = '<a href="#" class="btn btn-danger btn-sm" disabled>Delete</a>';
                $button = '';
                $status = 'approved';
            }
            $table_body .= '<tr>
            <td>'.$i++.'</td>
                <td>'.$monthly_contribution_detail->member->name.'</td>
                <td>'.$monthly_contribution_detail->amount.'</td>
                <td>'.$monthly_contribution_detail->month.'</td>
                <td>'.$monthly_contribution_detail->year.'</td>
                <td>'.$status.'</td>
                <td>'.$approved_by.'</td>
                <td>'.$date_approved.'</td>
                '.$button.'
            </tr>';
        }


        return response()->json([
            'data' => $table_body,
        ]);
    }

}
