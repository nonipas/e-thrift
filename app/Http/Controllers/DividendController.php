<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\Helpers;
use App\Models\AnnualDividend;
use App\Models\AnnualDividendDetail;
use App\Models\Contribution;
use App\Models\User;

class DividendController extends Controller
{
    //store dividend
    public function store(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request, [
            'year' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        
        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //check if dividend for year already exists and it is approved
        $dividend = AnnualDividend::where('year', $request->year)->first();
        if($dividend){
            if($dividend->is_approved == 1){
                toastr()->error('Dividend for '.$request->year.' exists and is approved');
                return redirect()->back();
            }
        }else{
            //create dividend
            $dividend = AnnualDividend::create([
                'year' => $request->year,
                'total_amount' => $request->amount,
                'total_dividend' => $request->amount,
            ]);
        }

        $amount_to_share = $request->amount;

        //get all contributions so far
        $contributions = Contribution::all();

        //check if member is active
        $contributions = $contributions->where('member.status', 1);

        //get total contribution so far
        $total_contribution = $contributions->sum('balance');

        //loop through contributions
        foreach($contributions as $contribution){

            $total_dividend = ($contribution->balance / $total_contribution) * $amount_to_share;

            //check if dividend detail exists for member
            $dividend_detail = AnnualDividendDetail::where('annual_dividend_id', $dividend->id)->where('member_id', $contribution->member_id)->first();

            if($dividend_detail){
                if($dividend_detail->is_approved == 1){
                    continue;
                }
                $dividend_detail->amount = $total_dividend;
                $dividend_detail->save();
            }else{
                //store in annual dividend details table
                AnnualDividendDetail::create([
                    'annual_dividend_id' => $dividend->id,
                    'member_id' => $contribution->member_id,
                    'amount' => $total_dividend,
                    'status' => 1,
                    'year' => $request->year,
                ]);
            }
        }

        //update dividend total
        $dividend->total_dividend = $dividend->annualDividendDetails->sum('amount');
        $dividend->save();

        //store activity
        Helpers::storeActivity('dividend', 'generated dividend for '.$request->year);

        toastr()->success('Dividend generated successfully for '.$request->year);
        return redirect()->back();
    
    }

    //approve dividend
    public function approve(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request, [
            'year' => 'required',
        ]);
        
        //return error if validation fails
        $errors = [];
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
                $errors[] = $error;
            }
            return response()->json(['status'=>false,'message' => implode(', ',$errors)]);
        }

        //get dividend
        $dividend = AnnualDividend::where('year', $request->year)->first();


        //check if dividend exists
        if(!$dividend){
            toastr()->error('Dividend not found');
            return response()->json(['status'=>false,'message' => 'Dividend not found']);
        }

        //check if dividend is approved
        if($dividend->is_approved == 1){
            toastr()->error('Dividend already approved');
            return response()->json(['status'=>false,'message' => 'Dividend already approved']);
        }

        //get all dividend details
        $dividend_details = AnnualDividendDetail::where('annual_dividend_id', $dividend->id)->get();

        //approve dividend details
        foreach($dividend_details as $detail){
            //if dividend detail is already approved, skip
            if($detail->is_approved == 1){
                continue;
            }
            
            $detail->is_approved = 1;
            $detail->approved_by = auth()->user()->id ?? 0;
            $detail->approved_at = date('Y-m-d H:i:s');
            $detail->save();
        }

        //put monthly contribution details in a table body
        $table_body = '';
        $i = 1;

        foreach($dividend_details as $dividend_detail){
            //get approved by (user)
            $approved_by = User::find($dividend_detail->approved_by) ?? '';
            $date_approved = $dividend_detail->approved_at ? date('d M Y H:i:s',strtotime($dividend_detail->approved_at)):'';
            //if the monthly contribution has been approved, disable the approve button and delete button
            $approve_button = '<a href="'.route('dividend.approve_detail', $dividend_detail->id).'" class="btn btn-success btn-sm">Approve</a>';
            $delete_button = '<a href="'.route('dividend.delete', $dividend_detail->id).'" class="btn btn-danger btn-sm">Delete</a>';

            //table date for button
            $button = '<td>'.$approve_button.$delete_button.'</td>';

            $status = 'unapproved';
            if($dividend_detail->is_approved){
                $button = '<a href="'.route('dividend.delete', $dividend_detail->id).'" class="btn btn-danger btn-sm">Delete</a>';
                $status = 'approved';
            }
            $table_body .= '<tr>
            <td>'.$i++.'</td>
                <td>'.$dividend_detail->member->name.'</td>
                <td>'.$dividend_detail->amount.'</td>
                <td>'.$dividend_detail->year.'</td>
                <td>'.$status.'</td>
                <td>'.$approved_by.'</td>
                <td>'.$date_approved.'</td>
                '.$button.'
            </tr>';
        }

        //approve dividend
        $dividend->is_approved = 1;
        $dividend->approved_by = auth()->user()->id ?? 0;
        $dividend->approved_at = date('Y-m-d H:i:s');
        $dividend->save();

        //store activity
        Helpers::storeActivity('dividend', 'approved dividend for '.$request->year);

        toastr()->success('Dividend approved successfully');
        return response()->json(['status'=>true,'message' => 'Dividend approved successfully','data' => $table_body]);
    }

    //approve dividend by id
    public function approveById(Request $request, $id){

        //if request id is set
        if($request->id){
            $id = $request->id;
        }

        //get dividend
        $dividend = AnnualDividend::find($id);

        //check if dividend exists
        if(!$dividend){
            toastr()->error('Dividend not found');
            return response()->json(['status'=>false,'message' => 'Dividend not found']);
        }

        //check if dividend is approved
        if($dividend->is_approved == 1){
            toastr()->error('Dividend already approved');
            return response()->json(['status'=>false,'message' => 'Dividend already approved']);
        }

        //get all dividend details
        $dividend_details = AnnualDividendDetail::where('annual_dividend_id', $dividend->id)->get();

        //approve dividend details
        foreach($dividend_details as $detail){
            $detail->is_approved = 1;
            $detail->approved_by = auth()->user()->id ?? 0;
            $detail->approved_at = date('Y-m-d H:i:s');
            $detail->save();
        }

        //approve dividend
        $dividend->is_approved = 1;
        $dividend->approved_by = auth()->user()->id ?? 0;
        $dividend->approved_at = date('Y-m-d H:i:s');
        $dividend->save();

        //store activity
        Helpers::storeActivity('dividend', 'approved dividend for '.$dividend->year);

        toastr()->success('Dividend approved successfully');
        return response()->json(['status'=>true,'message' => 'Dividend approved successfully']);

    }

    //approve dividend detail
    public function approveDetail(Request $request, $id){

        //get dividend detail
        $dividend_detail = AnnualDividendDetail::find($id);

        //check if dividend detail exists
        if(!$dividend_detail){
            toastr()->error('Dividend detail not found');
            return redirect()->back();
        }

        //check if dividend detail is approved
        if($dividend_detail->is_approved == 1){
            toastr()->error('Dividend detail already approved');
            return redirect()->back();
        }

        //approve dividend detail
        $dividend_detail->is_approved = 1;
        $dividend_detail->approved_by = auth()->user()->id ?? 0;
        $dividend_detail->approved_at = date('Y-m-d H:i:s');
        $dividend_detail->save();

        //check if all dividend details are approved and approve dividend
        $dividend = AnnualDividend::find($dividend_detail->annual_dividend_id);
        $dividend_details = AnnualDividendDetail::where('annual_dividend_id', $dividend->id)->get();

        if($dividend_details->where('is_approved', 0)->count() == 0){
            $dividend->is_approved = 1;
            $dividend->approved_by = auth()->user()->id ?? 0;
            $dividend->approved_at = date('Y-m-d H:i:s');
            $dividend->save();
        }

        //store activity
        Helpers::storeActivity('dividend', 'approved dividend for '.$dividend_detail->member->name);

        toastr()->success('Dividend detail approved successfully');
        return redirect()->back();
    }

    //delete dividend
    public function delete(Request $request, $id){

        if($request->id){
            $id = $request->id;
        }

        //get dividend
        $dividend = AnnualDividend::find($id);

        //check if dividend exists
        if(!$dividend){
            return response()->json(['status'=>false,'message' => 'Dividend not found']);
        }

        //check if dividend is approved
        if($dividend->is_approved == 1){
            return response()->json(['status'=>false,'message' => 'Dividend already approved']);
        }

        //get all dividend details
        $dividend_details = AnnualDividendDetail::where('annual_dividend_id', $dividend->id)->get();

        //delete dividend details
        foreach($dividend_details as $detail){
            $detail->delete();
        }

        //delete dividend
        $dividend->delete();

        //store activity
        Helpers::storeActivity('dividend', 'deleted dividend for '.$dividend->year);

        
        return response()->json(['status'=>true,'message' => 'Dividend deleted successfully']);
    }

    //delete dividend detail
    public function deleteDetail(Request $request, $id){

        //get dividend detail
        $dividend_detail = AnnualDividendDetail::find($id);

        //check if dividend detail exists
        if(!$dividend_detail){
            toastr()->error('Dividend detail not found');
            return redirect()->back();
        }

        //check if dividend detail is approved
        if($dividend_detail->is_approved == 1){
            toastr()->error('Dividend detail already approved');
            return redirect()->back();
        }

        //delete dividend detail
        $dividend_detail->delete();

        //store activity
        Helpers::storeActivity('dividend', 'deleted dividend for '.$dividend_detail->member->name);

        toastr()->success('Dividend detail deleted successfully');
        return redirect()->back();
    }

    //search dividend
    public function search(Request $request){
        //validate request using validate request from helper class
        $validate = Helpers::validateRequest($request, [
            'year' => 'required',
        ]);
        
        //return error if validation fails
        $errors = [];
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
                $errors[] = $error;
            }
            return response()->json([
                'status'=>false,
                'message' => implode(', ',$errors),
                'data' => '<tr><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>'
            ]);
        }

        //get dividend
        $dividend = AnnualDividend::where('year', $request->year)->first();

        //check if dividend exists
        if(!$dividend){
            toastr()->error('Dividend not found');
            return response()->json(['status'=>false,
            'message' => 'Dividend not found',
            'data' => '<tr><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>'
        ]);
        }

        //check if dividend is approved
        if($dividend->is_approved == 0){
            toastr()->error('Dividend not approved');
            return response()->json([
                'status'=>false,
                'message' => 'Dividend not approved',
                'data'=>'<tr><td valign="top" colspan="8" class="dataTables_empty">No data available in table</td></tr>']);
        }

        //get dividend details
        $dividend_details = AnnualDividendDetail::where('annual_dividend_id', $dividend->id)->get();
        //put monthly contribution details in a table body
        $table_body = '';
        $i = 1;

        foreach($dividend_details as $dividend_detail){
            //get approved by (user)
            $approved_by = User::find($dividend_detail->approved_by) ?? '';
            $date_approved = $dividend_detail->approved_at ? date('d M Y H:i:s',strtotime($dividend_detail->approved_at)):'';
            //if the monthly contribution has been approved, disable the approve button and delete button
            $approve_button = '<a href="'.route('dividend.approve_detail', $dividend_detail->id).'" class="btn btn-success btn-sm">Approve</a>';
            $delete_button = '<a href="'.route('dividend.delete', $dividend_detail->id).'" class="btn btn-danger btn-sm">Delete</a>';

            //table date for button
            $button = '<td>'.$approve_button.$delete_button.'</td>';

            $status = 'unapproved';
            if($dividend_detail->is_approved){
                $button = '<a href="'.route('dividend.delete', $dividend_detail->id).'" class="btn btn-danger btn-sm">Delete</a>';
                $status = 'approved';
            }
            $table_body .= '<tr>
            <td>'.$i++.'</td>
                <td>'.$dividend_detail->member->name.'</td>
                <td>'.$dividend_detail->amount.'</td>
                <td>'.$dividend_detail->year.'</td>
                <td>'.$status.'</td>
                <td>'.$approved_by.'</td>
                <td>'.$date_approved.'</td>
                '.$button.'
            </tr>';
        }

        //return dividend details
        return response()->json(['status'=>true,'data' => $table_body]);
    }

}
