<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Helpers\Helpers;
use App\Models\MonthlyRepayment;
use App\Models\MonthlyRepaymentDetail;
use App\Models\User;

class LoanController extends Controller
{
    //store new loan
    public function store(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request,[
            'amount' => 'required',
            'interest' => 'numeric',
            'account_no' => 'required|numeric|min:10',
            'bank' => 'required',
            'name' => 'required',
            'duration' => 'numeric',
            'start_month' => 'required',
            'start_year' => 'required',
            'monthly_repayment' => 'required|numeric',
            'previous_payment' => 'numeric',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        $member = 0;
        $beneficiary_type = 'non-member';
        $previous_payment = 0;

        //check if member

        if ($request->member){
            $member = $request->member;
            $beneficiary_type = 'member';

            //check if member has an active loan
            $active_loan = Loan::where('member_id',$member)->where('repayment_status','active')->first();
            if($active_loan){
                toastr()->error('Member has an active loan');
                return redirect()->back();
            }
        }

        //check if previous payment request
        if($request->previous_payment != null){
            $previous_payment = $request->previous_payment;
        }


        //check if loan with account
        $active_loan = Loan::where('beneficiary_account_no',$request->account_no)->where('repayment_status','active')->first();
        if($active_loan){
            toastr()->error('Active loan with account number already exists');
            return redirect()->back();
        }
        
        //create new loan
        $loan = Loan::create([
            'amount' => $request->amount,
            'interest' => $request->interest/100,
            'beneficiary_account_no' => $request->account_no,
            'beneficiary_bank' => $request->bank,
            'beneficiary_name' => $request->name,
            'duration' => $request->duration,
            'repayment_start_month' => $request->start_month,
            'repayment_start_year' => $request->start_year, 
            'monthly_repayment' => $request->monthly_repayment,
            'previous_payment' => $previous_payment,
            'total_repayment' => $previous_payment,
            'balance' => $request->amount - $previous_payment,
            'member_id' => $member,
            'beneficiary_type' => $beneficiary_type,
            'repayment_status' => 'active',
        ]);

        if($loan){

            //store activity
            Helpers::storeActivity('created loan for '.$request->name);

            toastr()->success('Loan created successfully');
            return redirect()->back();
        }

        toastr()->error('Loan creation failed');
        return redirect()->back();
        
    }

    //update loan
    public function update(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request,[
            'amount' => 'required',
            'interest' => 'numeric',
            'account_no' => 'required|numeric|min:10',
            'bank' => 'required',
            'name' => 'required',
            'duration' => 'numeric',
            'start_month' => 'required',
            'start_year' => 'required',
            'monthly_repayment' => 'required|numeric',
            'previous_payment' => 'required|numeric',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        $previous_payment = 0;
        
        //check if previous payment request
        if($request->previous_payment != null){
            $previous_payment = $request->previous_payment;
        }

        //update loan
        $loan = Loan::find($request->id);

        //check if loan exists
        if(!$loan){
            toastr()->error('Loan does not exist');
            return redirect()->back();
        }

        $loan->amount = $request->amount;
        $loan->interest = $request->interest/100;
        $loan->beneficiary_account_no = $request->account_no;
        $loan->beneficiary_bank = $request->bank;
        $loan->beneficiary_name = $request->name;
        $loan->duration = $request->duration;
        $loan->repayment_start_month = $request->start_month;
        $loan->repayment_start_year = $request->start_year; 
        $loan->monthly_repayment = $request->monthly_repayment;
        $loan->balance = $request->amount - ($previous_payment + ($loan->total_repayment - $loan->previous_payment));
        $loan->total_repayment = $request->amount - $loan->balance;
        $loan->previous_payment = $previous_payment;

        if($loan->save()){
            //store activity
            Helpers::storeActivity('updated loan for '.$request->name);
            toastr()->success('Loan updated successfully');
            return redirect()->back();
        }

        toastr()->error('Loan update failed');
        return redirect()->back();

    }

    //store top up loan
    public function storeTopUp(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request,[
            'amount' => 'required',
            'interest' => 'numeric',
            'duration' => 'numeric',
            'start_month' => 'required',
            'start_year' => 'required',
            'monthly_repayment' => 'required|numeric',
            'loan_id' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //get loan
        $loan = Loan::find($request->loan_id);

        //check if loan exists
        if(!$loan){
            toastr()->error('Loan does not exist');
            return redirect()->back();
        }

        //check if loan has been paid out
        if($loan->balance == 0 || $loan->repayment_status == 'completed'){
            toastr()->error('Loan repayment has been completed, please create a new loan');
            return redirect()->back();
        }

        //create new loan
        $loan = Loan::create([
            'amount' => $request->amount,
            'interest' => $request->interest/100,
            'beneficiary_account_no' => $loan->beneficiary_account_no,
            'beneficiary_bank' => $loan->beneficiary_bank,
            'beneficiary_name' => $loan->beneficiary_name,
            'duration' => $request->duration,
            'repayment_start_month' => $request->start_month,
            'repayment_start_year' => $request->start_year, 
            'monthly_repayment' => $request->monthly_repayment,
            'previous_payment' => 0,
            'total_repayment' => 0,
            'balance' => $request->amount,
            'member_id' => $loan->member_id,
            'beneficiary_type' => $loan->beneficiary_type,
            'repayment_status' => 'active',
            'type' => 'top-up',
            'parent_loan_id' => $loan->id,
        ]);

        if($loan){
            //store activity
            Helpers::storeActivity('created top up loan for '.$loan->beneficiary_name);
            toastr()->success('Loan created successfully');
            return redirect()->back();
        }

        toastr()->error('Loan creation failed');
        return redirect()->back();

    }


    //deactivate loan repayment status
    public function deactivate(Request $request, $id){
        if ($request->id){
            $id = $request->id;
        }

        $loan = Loan::find($id);
        $loan->repayment_status = 'inactive';

        if($loan->save()){
            //store activity
            Helpers::storeActivity('deactivated loan for '.$loan->beneficiary_name);
            toastr()->success('Loan deactivated successfully');
            return redirect()->back();
        }

        toastr()->error('Loan deactivation failed');
        return redirect()->back();
    }

    //activate loan repayment status
    public function activate(Request $request, $id){
        if ($request->id){
            $id = $request->id;
        }

        $loan = Loan::find($id);
        $loan->repayment_status = 'active';

        if($loan->save()){
            //store activity
            Helpers::storeActivity('activated loan for '.$loan->beneficiary_name);
            toastr()->success('Loan activated successfully');
            return redirect()->back();
        }

        toastr()->error('Loan activation failed');
        return redirect()->back();
    }

    //generate monthly repayment
    public function generateMonthlyRepayment(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request,[
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

        //get all active loans
        $loans = Loan::where('repayment_status','active')->get();

        //check if approved monthly repayment exists
        $monthly_repayment = MonthlyRepayment::where('month',$request->month)->where('year',$request->year)->first();
        if($monthly_repayment){

            if ($monthly_repayment->is_approved == 1){
                toastr()->error('Approved Monthly repayment already exists');
                return redirect()->back();
            }

        }else {
            //create monthly repayment
            $monthly_repayment = MonthlyRepayment::create([
                'month' => $request->month,
                'year' => $request->year,
            ]);
        }

        //loop through loans
        foreach($loans as $loan){

            //check if loan has been paid out
            if($loan->balance == 0){
                continue;
            }

            //check if monthly repayment details exists
            $monthly_repayment_details = MonthlyRepaymentDetail::where('loan_id',$loan->id)->where('monthly_repayment_id',$monthly_repayment->id)->first();
            if($monthly_repayment_details){
                //if monthly repayment details is approved, don't update
                if($monthly_repayment_details->is_approved == 1){
                    continue;
                }
                //update monthly repayment details
                $monthly_repayment_details->amount = $loan->monthly_repayment;
                $monthly_repayment_details->save();
            }else{
                //create monthly repayment details
                $monthly_repayment_details = MonthlyRepaymentDetail::create([
                    'loan_id' => $loan->id,
                    'member_id' => $loan->member_id,
                    'monthly_repayment_id' => $monthly_repayment->id,
                    'amount' => $loan->monthly_repayment,
                    'month' => $request->month,
                    'year' => $request->year,
                ]);
            }

            //update monthly repayment total amount
            $monthly_repayment->total_amount = $monthly_repayment->total_amount + $monthly_repayment_details->amount;
            $monthly_repayment->save();

        }
        

        //store activity
        Helpers::storeActivity('generated monthly repayment for '.$request->month.' '.$request->year);
        toastr()->success('Monthly repayment generated successfully');
        return redirect()->back();
    }

    //approve monthly repayment
    public function approveMonthlyRepayment(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request,[
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


        //check if approved monthly repayment exists
        $monthly_repayment = MonthlyRepayment::where('month',$request->month)->where('year',$request->year)->first();
        if($monthly_repayment->is_approved == 1){
            toastr()->error('Approved Monthly repayment already exists');
            return redirect()->back();
        }

        //get monthly repayment details
        $monthly_repayment_details = MonthlyRepaymentDetail::where('monthly_repayment_id',$monthly_repayment->id)->get();

        //check if monthly repayment details exists
        if(count($monthly_repayment_details) == 0){
            toastr()->error('Monthly repayment details does not exist');
            return redirect()->back();
        }

        //loop through monthly repayment details
        foreach($monthly_repayment_details as $monthly_repayment_detail){
            //get loan
            $loan = Loan::find($monthly_repayment_detail->loan_id);

            //check if monthly repayment details exists
            $monthly_repayment_details = MonthlyRepaymentDetail::where('loan_id',$loan->id)->where('monthly_repayment_id',$monthly_repayment->id)->first();
            if(!$monthly_repayment_details){
                continue;
            }

            //update loan balance
            $loan->balance = $loan->balance - $monthly_repayment_detail->amount;
            $loan->save();

            //update monthly repayment details
            $monthly_repayment_detail->is_approved = 1;
            $monthly_repayment_detail->approved_by = auth()->user()->id ?? 0;
            $monthly_repayment_detail->approved_at = date('Y-m-d H:i:s');
            $monthly_repayment_detail->save();
        }

        //update monthly repayment
        $monthly_repayment->is_approved = 1;
        $monthly_repayment->approved_by = auth()->user()->id ?? 0;
        $monthly_repayment->approved_at = date('Y-m-d H:i:s');
        $monthly_repayment->save();
        //store activity
        Helpers::storeActivity('approved monthly repayment for '.$request->month.' '.$request->year);
        toastr()->success('Monthly repayment approved successfully');
        return redirect()->back();
    }

    //approve monthly repayment by id
    public function approveMonthlyRepaymentById(Request $request, $id){
        if ($request->id){
            $id = $request->id;
        }

        //get monthly repayment
        $monthly_repayment = MonthlyRepayment::find($id);

        //check if monthly repayment exists
        if(!$monthly_repayment){
            toastr()->error('Monthly repayment does not exist');
            return redirect()->back();
        }

        //check if approved monthly repayment exists
        if($monthly_repayment->is_approved == 1){
            toastr()->error('Approved Monthly repayment already exists');
            return redirect()->back();
        }

        //get monthly repayment details
        $monthly_repayment_details = MonthlyRepaymentDetail::where('monthly_repayment_id',$monthly_repayment->id)->get();

        //check if monthly repayment details exists
        if(count($monthly_repayment_details) == 0){
            toastr()->error('Monthly repayment details does not exist');
            return redirect()->back();
        }

        //loop through monthly repayment details
        foreach($monthly_repayment_details as $monthly_repayment_detail){
            //get loan
            $loan = Loan::find($monthly_repayment_detail->loan_id);

            //check if monthly repayment details exists
            $monthly_repayment_details = MonthlyRepaymentDetail::where('loan_id',$loan->id)->where('monthly_repayment_id',$monthly_repayment->id)->first();
            if(!$monthly_repayment_details){
                continue;
            }

            //update loan balance
            $loan->balance = $loan->balance - $monthly_repayment_detail->amount;
            $loan->save();

            //update monthly repayment details
            $monthly_repayment_detail->is_approved = 1;
            $monthly_repayment_detail->approved_by = auth()->user()->id ?? 0;
            $monthly_repayment_detail->approved_at = date('Y-m-d H:i:s');
            $monthly_repayment_detail->save();
        }

        //update monthly repayment
        $monthly_repayment->is_approved = 1;
        $monthly_repayment->approved_by = auth()->user()->id ?? 0;
        $monthly_repayment->approved_at = date('Y-m-d H:i:s');
        $monthly_repayment->save();
        //store activity
        Helpers::storeActivity('approved monthly repayment for '.$request->month.' '.$request->year);
        toastr()->success('Monthly repayment for '.$request->month.' '.$request->year.' was approved successfully');
        return redirect()->back();
    }

    //approve monthly repayment detail by id
    public function approveMonthlyRepaymentDetailById(Request $request, $id){
        if ($request->id){
            $id = $request->id;
        }

        //get monthly repayment detail
        $monthly_repayment_detail = MonthlyRepaymentDetail::find($id);

        //check if monthly repayment detail exists
        if(!$monthly_repayment_detail){
            toastr()->error('Monthly repayment detail does not exist');
            return redirect()->back();
        }

        //check if monthly repayment exists
        $monthly_repayment = MonthlyRepayment::find($monthly_repayment_detail->monthly_repayment_id);
        if(!$monthly_repayment){
            toastr()->error('Monthly repayment does not exist');
            return redirect()->back();
        }

        //check if approved monthly repayment exists
        if($monthly_repayment->is_approved == 1){
            toastr()->error('Approved Monthly repayment already exists');
            return redirect()->back();
        }

        //get loan
        $loan = Loan::find($monthly_repayment_detail->loan_id);

        //update loan balance
        $loan->balance = $loan->balance - $monthly_repayment_detail->amount;
        $loan->save();

        //update monthly repayment details
        $monthly_repayment_detail->is_approved = 1;
        $monthly_repayment_detail->approved_by = auth()->user()->id ?? 0;
        $monthly_repayment_detail->approved_at = date('Y-m-d H:i:s');
        $monthly_repayment_detail->save();

        //store activity
        Helpers::storeActivity('approved monthly repayment for '.$monthly_repayment_detail->member->name);

        toastr()->success('Monthly repayment was approved successfully');
        return redirect()->back();
    }

    //update repayment amount
    public function updateRepaymentAmount(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request,[
            'repayment_amount' => 'required|numeric',
            'repayment_id' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //get loan
        $repayment = MonthlyRepaymentDetail::find($request->repayment_id);

        //check if loan exists
        if(!$repayment){
            toastr()->error('repayment does not exist');
            return redirect()->back();
        }
        
        //check if it is approved
        if($repayment->is_approved == 1){
            toastr()->error('repayment has been approved');
            return redirect()->back();
        }

        //update monthly repayment details
        $repayment->amount = $request->repayment_amount;
        $repayment->save();

        //store activity
        Helpers::storeActivity('updated repayment amount for '.$repayment->member->name);

        toastr()->success('Repayment amount updated successfully');
        return redirect()->back();
    }

    //reject monthly repayment and delete by id
    public function rejectMonthlyRepaymentById(Request $request, $id){
        if ($request->id){
            $id = $request->id;
        }

        //get monthly repayment
        $monthly_repayment = MonthlyRepayment::find($id);

        //check if monthly repayment exists
        if(!$monthly_repayment){
            toastr()->error('Monthly repayment does not exist');
            return redirect()->back();
        }

        $del = false;

        if ($monthly_repayment->is_appproved == 0){
            $del = true;
        }


        //get monthly repayment details
        $monthly_repayment_details = MonthlyRepaymentDetail::where('monthly_repayment_id',$monthly_repayment->id)->get();

        //check if monthly repayment details exists
        if(count($monthly_repayment_details) == 0){
            //delete monthly repayment
            $monthly_repayment->delete();
            toastr()->success('Monthly repayment for '.$request->month.' '.$request->year.' was deleted successfully');
            return redirect()->back();
        }

        //loop through monthly repayment details
        foreach($monthly_repayment_details as $monthly_repayment_detail){
            //get loan
            $loan = Loan::find($monthly_repayment_detail->loan_id);

            //check if monthly repayment details exists
            $monthly_repayment_details = MonthlyRepaymentDetail::where('loan_id',$loan->id)->where('monthly_repayment_id',$monthly_repayment->id)->first();
            if(!$monthly_repayment_details){
                continue;
            }

            if($monthly_repayment_detail->is_approved == 1){
                 //update loan balance
                $loan->balance = $loan->balance + $monthly_repayment_detail->amount;
                $loan->save();
            }

            //update monthly repayment details
            $monthly_repayment_detail->is_approved = 0;
            $monthly_repayment_detail->approved_by = null;
            $monthly_repayment_detail->approved_at = null;
            $monthly_repayment_detail->save();

            if ($del){
                $monthly_repayment_detail->delete();
            }

        }

        //update monthly repayment
        $monthly_repayment->is_approved = 0;
        $monthly_repayment->approved_by = null;
        $monthly_repayment->approved_at = null;
        $monthly_repayment->save();

        if ($del){
            $monthly_repayment->delete();
        }

        //store activity
        Helpers::storeActivity('rejected monthly repayment for '.$request->month.' '.$request->year);

        toastr()->success('Monthly repayment for '.$request->month.' '.$request->year.' was rejected successfully');
        return redirect()->back();
    }

    //reject monthly repayment detail by id
    public function rejectMonthlyRepaymentDetail(Request $request, $id){
        if($request->id){
            $id = $request->id;
        }

        //get monthly repayment detail
        $monthly_repayment_detail = MonthlyRepaymentDetail::find($id);

        if (!$monthly_repayment_detail){
            toastr()->error('Monthly repayment detail does not exist');
            return redirect()->back();
        }

        if ($monthly_repayment_detail->is_appproved == 0){
            toastr()->success('Monthly repayment for '.$monthly_repayment_detail->member->name.' was rejected successfully');
            $monthly_repayment_detail->delete();
            return redirect()->back();
            
        }
        $loan = Loan::find($monthly_repayment_detail->loan_id);
        $loan->balance = $loan->balance + $monthly_repayment_detail->amount;
        $loan->save();
        
        //update monthly repayment details
        $monthly_repayment_detail->is_approved = 0;
        $monthly_repayment_detail->approved_by = null;
        $monthly_repayment_detail->approved_at = null;
        $monthly_repayment_detail->save();

        //store activity
        Helpers::storeActivity('rejected monthly repayment for '.$monthly_repayment_detail->member->name);

        toastr()->success('Monthly repayment approval for '.$monthly_repayment_detail->member->name.' was rejected successfully');
        return redirect()->back();

    }

        //search monthly repayment
        public function searchMonthlyrepayment(Request $request)
        {
    
            //get monthly repayment
            $monthly_repayment = MonthlyRepayment::where('month', $request->month)->where('year', $request->year)->first();
    
            //if monthly repayment does not exist, return empty table body
            if(!$monthly_repayment){
                return response()->json([
                    'data' => '<tr><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>',
                ]);
            }
    
            //get monthly repayment details
            $monthly_repayment_details = MonthlyRepaymentDetail::where('monthly_repayment_id', $monthly_repayment->id)->get();
    
            if(!$monthly_repayment){
                //get all monthly repayments details for the latest month
                $monthly_repayment = MonthlyRepayment::orderBy('id', 'desc')->first();
                $monthly_repayment_details = MonthlyRepaymentDetail::where('monthly_repayment_id', $monthly_repayment->id)->get();
            }
    
            //put monthly repayment details in a table body
            $table_body = '';
            $i = 1;
            foreach($monthly_repayment_details as $monthly_repayment_detail){
                $approved_by = User::find($monthly_repayment_detail->approved_by) ?? '';
                $date_approved = $monthly_repayment_detail->approved_at ? date('d M Y H:i:s',strtotime($monthly_repayment_detail->approved_at)):'';
                //if the monthly repayment has been approved, disable the approve button and delete button
                $approve_button = '<a href="'.route('loan.approve_monthly_member', $monthly_repayment_detail->id).'" class="btn btn-success btn-sm">Approve</a>';
                $update_button = '<a class="btn btn-success btn-sm" data-repayment-id="'.$monthly_repayment_detail->id.'" id="update-amount"
                href="#">Update Amount</a>';
                $delete_button = '<a href="'.route('loan.reject_monthly_member', $monthly_repayment_detail->id).'" class="btn btn-danger btn-sm">Reject</a>';

    
                //table date for button
                $button = '<td>'.$approve_button.$update_button.'</td>';
    
                $status = '<span class="text-danger">Pending</span>';
                if($monthly_repayment_detail->is_approved){
                    $button = '<td>'.$delete_button.'</td>';

                    $status = '<span class="text-success">Approved</span>';
                }

                if ($request->list == 1){
                    $button = '';
                }

                $table_body .= '<tr>
                <td>'.$i++.'</td>
                    <td>'.$monthly_repayment_detail->member->name.'</td>
                    <td>'.$monthly_repayment_detail->amount.'</td>
                    <td>'.$monthly_repayment_detail->month.'</td>
                    <td>'.$monthly_repayment_detail->year.'</td>
                    <td>'.$status.'</td>
                    <td>'.$approved_by->name.'</td>
                    <td>'.$date_approved.'</td>
                    '.$button.'
                </tr>';
            }
    
    
            return response()->json([
                'data' => $table_body,
            ]);
        }

}