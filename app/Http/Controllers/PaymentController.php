<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helpers;
use App\Exports\ProcessedPaymentExport;
use App\Exports\dataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PaymentBatch;
use App\Models\Payment;
use App\Models\Loan;
use App\Models\AnnualDividend;
use App\Models\AnnualDividendDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    //create paymnent batch
    public function createPaymentBatch(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'name' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //create payment batch name with helper class
        $name = Helpers::createBatchName($request->name);

        //create payment batch
        $create = PaymentBatch::create([
            'name' => $name,
            'status' => 1,
        ]);

        //return success message if payment batch is created
        if($create){
            //store activity
            Helpers::storeActivity('created payment batch - '.$name);

            toastr()->success('Payment Batch Created Successfully '. $name);
            return redirect()->back();
        }

        //return error message if payment batch is not created
        toastr()->error('Payment Batch Not Created');
        return redirect()->back();
    }

    //delete payment batch

    public function deletePaymentBatch($id)
    {
        //find payment batch
        $paymentBatch = PaymentBatch::find($id);

        //return error message if payment batch is not found
        if(!$paymentBatch){
            toastr()->error('Payment Batch Not Found');
            return redirect()->back();
        }

        //check if batch is approved or proecessed
        if($paymentBatch->is_approved == 1 || $paymentBatch->is_processed == 1){
            toastr()->error('Payment Batch Cannot Be Deleted');
            return redirect()->back();
        }

        //delete payment batch
        $delete = $paymentBatch->delete();

        //return success message if payment batch is deleted
        if($delete){
            //delete all payments in the batch
            $payments = Payment::where('payment_batch_id',$id)->get();

            foreach($payments as $payment){
                $type_id = $payment->type_id;
                $payment->delete();
                //update paid out status for loans and dividends
                if($payment->payment_type == 'loan'){
                    $loan = Loan::find($type_id);
                    $loan->paid_out = 0;
                    $loan->save();
                }
                if($payment->payment_type == 'dividend'){
                    $dividend = AnnualDividendDetail::find($type_id);
                    $dividend->paid_out = 0;
                    $dividend->save();
                }
            }
            //store activity
            Helpers::storeActivity('deleted payment batch - '.$paymentBatch->name);
            toastr()->success('Payment Batch Deleted Successfully');
            return redirect()->back();
        }

        //return error message if payment batch is not deleted
        toastr()->error('Payment Batch Not Deleted');
        return redirect()->back();
    }

    //store payment

    public function storePayment(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'amount' => 'required|numeric',
            'account_no' => 'required',
            'bank' => 'required',
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        $payment_batch_id = $request->batch;

        //check if account_no exist for the bank
        $check_account_no = Payment::where('beneficiary_account_no',$request->account_no)->where('bank',$request->bank)->where('batch_id',$payment_batch_id)->first();

        if($check_account_no){
            toastr()->error('Payment With The Account Number Already Exist For The Selected Bank in this Batch');
            return redirect()->back();
        }

        //if no batch selected create new batch with name and category
        if($request->batch == ''){
            //create payment batch name with helper class
            $name = Helpers::createBatchName($request->category);

            //create payment batch
            $batch = PaymentBatch::create([
                'name' => $name,
                'status' => 1,
            ]);

            $payment_batch_id = $batch->id;
        }

        //create payment
        $create = Payment::create([
            'payment_batch_id' => $payment_batch_id,
            'amount' => $request->amount,
            'beneficiary_account_no' => $request->account_no,
            'bank' => $request->bank,
            'beneficiary_name' => $request->name,
            'payment_type' => $request->category,
            'description' => $request->description,

        ]);

        //return success message if payment is created
        if($create){
            // add amount to payment batch total amount
            $batch = PaymentBatch::find($payment_batch_id);
            $batch->total_amount = $batch->total_amount + $request->amount;
            $batch->size = $batch->size + 1;
            $batch->save();

            //store activity
            Helpers::storeActivity('created payment for '.$request->name);

            toastr()->success('Payment Created Successfully');
            return redirect()->back();
        }

        //return error message if payment is not created
        toastr()->error('Payment Not Created');
        return redirect()->back();
    }

    //delete payment
    public function deletePayment($id)
    {
        //find payment
        $payment = Payment::find($id);

        //return error message if payment is not found
        if(!$payment){
            toastr()->error('Payment Not Found');
            return redirect()->back();
        }

        //check if payment is approved or proecessed
        if($payment->is_approved == 1 || $payment->is_processed == 1){
            toastr()->error('Payment Cannot Be Deleted');
            return redirect()->back();
        }

        $batch_id = $payment->payment_batch_id;
        $type_id = $payment->type_id;

        //delete payment
        $delete = $payment->delete();

        //return success message if payment is deleted
        if($delete){
            //remove amount from payment batch total amount
            $batch = PaymentBatch::find($batch_id);
            $batch->total_amount = $batch->total_amount - $payment->amount;
            $batch->save();

            //update paid out status for loans and dividends
            if($payment->payment_type == 'loan'){
                $loan = Loan::find($type_id);
                $loan->paid_out = 0;
                $loan->save();
            }
            if($payment->payment_type == 'dividend'){
                $dividend = AnnualDividendDetail::find($type_id);
                $dividend->paid_out = 0;
                $dividend->save();
            }

            //store activity
            Helpers::storeActivity('deleted payment for '.$payment->beneficiary_name);

            toastr()->success('Payment Deleted Successfully');
            return redirect()->back();
        }

        //return error message if payment is not deleted
        toastr()->error('Payment Not Deleted');
        return redirect()->back();
    }

    //add all loans that are not paid out to payment batch
    private function addLoansToPaymentBatch(Request $request)
    {

        //get all loans that are not paid out
        $loans = Loan::where('paid_out',0)->where('repayment_status','active')->get();

        //return error message if no loans are found
        if(count($loans) == 0){
            toastr()->error('No Loans Found');
            return response()->json([
                'status' => 'error',
                'message' => 'No Loans Found',
            ]);
        }

        //loop through loans and add them to payment batch
        foreach($loans as $loan){
            //create payment
            $create = Payment::create([
                'payment_batch_id' => $request->batch,
                'amount' => $loan->amount,
                'beneficiary_account_no' => $loan->beneficiary_account_no,
                'bank' => $loan->beneficiary_bank,
                'beneficiary_name' => $loan->beneficiary_name,
                'payment_type' => $request->category,
                'description' => $request->narration ?? 'Loan Payment',
                'type_id' => $loan->id,
            ]);

            //return success message if payment is created
            if($create){
                // add amount to payment batch total amount
                $batch = PaymentBatch::find($request->batch);
                $batch->total_amount = $batch->total_amount + $loan->amount;
                $batch->size = $batch->size + 1;
                $batch->save();

                //update loan paid out status
                $loan->paid_out = 1;
                $loan->save();
            }

        }

        //store activity
        Helpers::storeActivity('added loans to payment batch');

        toastr()->success('Loans Added To Payment Batch Successfully');
        return response()->json([
            'status' => 'success',
            'message' => 'Loans Added To Payment Batch Successfully',
        ]);
    }

    //add all dividends that are not paid out to payment batch
    private function addDividendsToPaymentBatch(Request $request)
    {

        //get all dividends that are not paid out
        $dividends = AnnualDividendDetail::where('paid_out',0)->where('is_approved',1)->get();

        //return error message if no dividends are found
        if(count($dividends) == 0){
            toastr()->error('No Dividends Found');
            return response()->json([
                'status' => 'error',
                'message' => 'No Dividends Found',
            ]);
        }

        //loop through dividends and add them to payment batch
        foreach($dividends as $dividend){
            //create payment
            $create = Payment::create([
                'payment_batch_id' => $request->batch,
                'amount' => $dividend->amount,
                'beneficiary_account_no' => $dividend->beneficiary_account_no,
                'bank' => $dividend->beneficiary_bank,
                'beneficiary_name' => $dividend->beneficiary_name,
                'payment_type' => $request->category,
                'description' => $request->narration ?? 'Dividend Payment',
                'type_id' => $dividend->id,
            ]);

            //return success message if payment is created
            if($create){
                // add amount to payment batch total amount
                $batch = PaymentBatch::find($request->batch);
                $batch->total_amount = $batch->total_amount + $dividend->amount;
                $batch->size = $batch->size + 1;
                $batch->save();

                //update dividend paid out status
                $dividend->paid_out = 1;
                $dividend->save();
            }
        }

        //store activity
        Helpers::storeActivity('added dividends to payment batch');

        toastr()->success('Dividends Added To Payment Batch Successfully');
        return response()->json([
            'status' => 'success',
            'message' => 'Dividends Added To Payment Batch Successfully',
        ]);
    }

    //add system payments to payment batch
    public function addSystemPaymentsToBatch(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'batch' => 'required',
            'category' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return response()->json([
                'status' => 'error',
                'message' => implode(',',$validate),
            ]);
        }

        if($request->category == 'loan'){
            $response = $this->addLoansToPaymentBatch($request);
            return $response;
        }else{
            $response = $this->addDividendsToPaymentBatch($request);
            return $response;
        }
    }

    //approve payment batch
    public function approvePaymentBatch($id)
    {
        //find payment batch
        $paymentBatch = PaymentBatch::find($id);

        //return error message if payment batch is not found
        if(!$paymentBatch){
            toastr()->error('Payment Batch Not Found');
            return redirect()->back();
        }

        //check if payment batch is already approved
        if($paymentBatch->is_approved == 1){
            toastr()->error('Payment Batch Already Approved');
            return redirect()->back();
        }

        //approve payment batch
        $paymentBatch->is_approved = 1;
        $paymentBatch->approved_by = auth()->user()->id ?? 0;
        $paymentBatch->approved_at = date('Y-m-d H:i:s');
        $paymentBatch->save();

        //approve all payments in the batch
        $payments = Payment::where('payment_batch_id',$id)->where('is_approved',0)->get();

        foreach($payments as $payment){
            $payment->is_approved = 1;
            $payment->approved_by = auth()->user()->id ?? 0;
            $payment->approved_at = date('Y-m-d H:i:s');
            $payment->save();
        }

        //store activity
        Helpers::storeActivity('approved payment batch - '.$paymentBatch->name);

        //return success message if payment batch is approved
        toastr()->success('Payment Batch Approved Successfully');
        return redirect()->back();
    }

    //approve payment
    public function approvePayment($id)
    {
        //find payment
        $payment = Payment::find($id);

        //return error message if payment is not found
        if(!$payment){
            toastr()->error('Payment Not Found');
            return redirect()->back();
        }

        //check if payment is already approved
        if($payment->is_approved == 1){
            toastr()->error('Payment Already Approved');
            return redirect()->back();
        }

        //approve payment
        $payment->is_approved = 1;
        $payment->approved_by = auth()->user()->id ?? 0;
        $payment->approved_at = date('Y-m-d H:i:s');
        $payment->save();

        //store activity
        Helpers::storeActivity('approved payment for '.$payment->beneficiary_name);

        //return success message if payment is approved
        toastr()->success('Payment Approved Successfully');
        return redirect()->back();
    }

    //reject payment batch
    public function rejectPaymentBatch($id)
    {
        //find payment batch
        $paymentBatch = PaymentBatch::find($id);

        //return error message if payment batch is not found
        if(!$paymentBatch){
            toastr()->error('Payment Batch Not Found');
            return redirect()->back();
        }

        //reject payment batch
        $paymentBatch->is_approved = 0;
        $paymentBatch->approved_by = null;
        $paymentBatch->approved_at = null;
        $paymentBatch->save();

        //reject all payments in the batch
        $payments = Payment::where('payment_batch_id',$id)->where('is_approved',0)->get();

        foreach($payments as $payment){
            $payment->is_approved = 0;
            $payment->approved_by = null;
            $payment->approved_at = null;
            $payment->save();
        }

        //store activity
        Helpers::storeActivity('rejected payment batch - '.$paymentBatch->name);

        //return success message if payment batch is rejected
        toastr()->success('Payment Batch Rejected Successfully');
        return redirect()->back();
    }

    //approve selected payment batch
    public function approveSelectedPaymentBatch(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'payment_batches' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        foreach($request->payment_batches as $payment_batch){
            //find payment batch
            $paymentBatch = PaymentBatch::find($payment_batch);

            //return error message if payment batch is not found
            if(!$paymentBatch){
                toastr()->error('Payment Batch Not Found');
                return redirect()->back();
            }

            //check if payment batch is already approved
            if($paymentBatch->is_approved == 1){
                toastr()->error('Payment Batch Already Approved');
                return redirect()->back();
            }

            //approve payment batch
            $paymentBatch->is_approved = 1;
            $paymentBatch->approved_by = auth()->user()->id ?? 0;
            $paymentBatch->approved_at = date('Y-m-d H:i:s');
            $paymentBatch->save();

            //approve all payments in the batch
            $payments = Payment::where('payment_batch_id',$payment_batch)->where('is_approved',0)->get();

            foreach($payments as $payment){
                $payment->is_approved = 1;
                $payment->approved_by = auth()->user()->id ?? 0;
                $payment->approved_at = date('Y-m-d H:i:s');
                $payment->save();
            }
        }

        //store activity
        Helpers::storeActivity('approved payment batches');

        //return success message if payment batch is approved
        toastr()->success('Payment Batches Approved Successfully');
        return redirect()->back();
    }

    //approve selected payments
    public function approveSelectedPayments(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'payments' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            toastr()->error('No payments selected');
            return redirect()->back();
        }

        foreach($request->payments as $payment){
            //find payment
            $payment = Payment::find($payment);

            //return error message if payment is not found
            if(!$payment){
                toastr()->error('Payment Not Found');
                return redirect()->back();
            }

            //check if payment is already approved
            if($payment->is_approved == 1){
                toastr()->error('Payment Already Approved');
                return redirect()->back();
            }

            //approve payment
            $payment->is_approved = 1;
            $payment->approved_by = auth()->user()->id ?? 0;
            $payment->approved_at = date('Y-m-d H:i:s');
            $payment->save();
        }

        //update batch status if all payments in the batch are approved
        $batch = PaymentBatch::find($payment->payment_batch_id);
        $payments = Payment::where('payment_batch_id',$batch->id)->where('is_approved',0)->get();

        if(count($payments) == 0){
            $batch->is_approved = 1;
            $batch->approved_by = auth()->user()->id ?? 0;
            $batch->approved_at = date('Y-m-d H:i:s');
            $batch->save();
        }

        //store activity
        Helpers::storeActivity('approved payments in batch - '.$batch->name);

        //return success message if payment is approved
        toastr()->success('Payments Approved Successfully');
        return redirect()->back();
    }

    //process selected payments
    public function processSelectedPayments(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'payments' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            toastr()->error('No payments selected');
            return redirect()->back();
        }

        foreach($request->payments as $payment){
            //find payment
            $payment = Payment::find($payment);

            //return error message if payment is not found
            if(!$payment){
                toastr()->error('Payment Not Found');
                return redirect()->back();
            }

            //check if payment is already processed
            if($payment->is_processed == 1){
                toastr()->error('Payment Already Processed');
                return redirect()->back();
            }

            //process payment
            $payment->is_processed = 1;
            $payment->processed_by = auth()->user()->id ?? 0;
            $payment->processed_at = date('Y-m-d H:i:s');
            $payment->save();
        }

        //update batch status if all payments in the batch are processed
        $batch = PaymentBatch::find($payment->payment_batch_id);
        $payments = Payment::where('payment_batch_id',$batch->id)->where('is_processed',0)->get();

        if(count($payments) == 0){
            $batch->is_processed = 1;
            $batch->processed_by = auth()->user()->id ?? 0;
            $batch->processed_at = date('Y-m-d H:i:s');
            $batch->save();
        }

        //store activity
        Helpers::storeActivity('processed payments in batch - '.$batch->name);

        //return success message if payment is processed
        toastr()->success('Payments Processed Successfully');
        return redirect()->back();
    }

    //process selected payment batch
    public function processSelectedPaymentBatch(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'payment_batches' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            toastr()->error('No payment batches selected');
            return response()->json([
                'status' => false,
                'message' => 'No payment batches selected',
            ]);
        }

        foreach($request->payment_batches as $payment_batch){
            //find payment batch
            $paymentBatch = PaymentBatch::find($payment_batch);

            //return error message if payment batch is not found
            if(!$paymentBatch){
                toastr()->error('Payment Batch Not Found');
                return response()->json([
                    'status' => false,
                    'message' => 'Payment Batch Not Found',
                ]);
            }

            //check if payment batch is already processed
            if($paymentBatch->is_processed == 1){
                toastr()->error('Payment Batch Already Processed');
                return response()->json([
                    'status' => false,
                    'message' => 'Payment Batch Already Processed',
                ]);
            }

            //process payment batch
            $paymentBatch->is_processed = 1;
            $paymentBatch->processed_by = auth()->user()->id ?? 0;
            $paymentBatch->processed_at = date('Y-m-d H:i:s');
            $paymentBatch->save();

            //process all payments in the batch
            $payments = Payment::where('payment_batch_id',$payment_batch)->where('is_processed',0)->get();

            foreach($payments as $payment){
                $payment->is_processed = 1;
                $payment->processed_by = auth()->user()->id ?? 0;
                $payment->processed_at = date('Y-m-d H:i:s');
                $payment->save();
            }
        }

        //store activity
        Helpers::storeActivity('processed payment batches');

        //return success message if payment batch is processed
        toastr()->success('Payment Batches Processed Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Payment Batches Processed Successfully',
        ]);
    }

    //reject payment
    public function rejectPayment($id)
    {
        //find payment
        $payment = Payment::find($id);

        //return error message if payment is not found
        if(!$payment){
            toastr()->error('Payment Not Found');
            return redirect()->back();
        }

        //reject payment
        $payment->is_approved = 0;
        $payment->approved_by = null;
        $payment->approved_at = null;
        $payment->save();

        //store activity
        Helpers::storeActivity('rejected payment for '.$payment->beneficiary_name);

        //return success message if payment is rejected
        toastr()->success('Payment Rejected Successfully');
        return redirect()->back();
    }

    //reject selected payments
    public function rejectSelectedPayments(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'payments' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return response()->json([
                'status' => false,
                'message' => implode(',',$validate),
            ]);
        }

        foreach($request->payments as $payment){
            //find payment
            $payment = Payment::find($payment);

            //return error message if payment is not found
            if(!$payment){
                toastr()->error('Payment Not Found');
                return response()->json([
                    'status' => false,
                    'message' => 'Payment Not Found',
                ]);
            }

            //reject payment
            $payment->is_approved = 0;
            $payment->approved_by = null;
            $payment->approved_at = null;
            $payment->save();
        }

        //update batch status if any payments in the batch are rejected
        $batch = PaymentBatch::find($request->batch_id);
        $batch->is_approved = 0;
        $batch->approved_by = null;
        $batch->approved_at = null;
        $batch->save();

        //store activity
        Helpers::storeActivity('rejected payments in batch - '.$batch->name);

        //return success message if payment is rejected
        toastr()->success('Payments Rejected Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Payments Rejected Successfully',
        ]);
    }

    //reject selected payment batch
    public function rejectSelectedPaymentBatch(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'payment_batches' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return response()->json([
                'status' => false,
                'message' => implode(',',$validate),
            ]);
        }

        foreach($request->payment_batches as $payment_batch){
            //find payment batch
            $paymentBatch = PaymentBatch::find($payment_batch);

            //return error message if payment batch is not found
            if(!$paymentBatch){
                toastr()->error('Payment Batch Not Found');
                return response()->json([
                    'status' => false,
                    'message' => 'Payment Batch Not Found',
                ]);
            }

            //reject payment batch
            $paymentBatch->is_approved = 0;
            $paymentBatch->approved_by = null;
            $paymentBatch->approved_at = null;
            $paymentBatch->save();

            //reject all payments in the batch
            $payments = Payment::where('payment_batch_id',$payment_batch)->where('is_approved',0)->get();

            foreach($payments as $payment){
                $payment->is_approved = 0;
                $payment->approved_by = null;
                $payment->approved_at = null;
                $payment->save();
            }
        }

        //store activity
        Helpers::storeActivity('rejected payment batches');

        //return success message if payment batch is rejected
        toastr()->success('Payment Batches Rejected Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Payment Batches Rejected Successfully',
        ]);
    }

  //process payment batch
    public function processPaymentBatch($id)
    {
        //find payment batch
        $paymentBatch = PaymentBatch::find($id);
    
        //return error message if payment batch is not found
        if(!$paymentBatch){
            toastr()->error('Payment Batch Not Found');
            return redirect()->back();
        }
    
        //check if payment batch is already processed
        if($paymentBatch->is_processed == 1){
            toastr()->error('Payment Batch Already Processed');
            return redirect()->back();
        }
    
        //process payment batch
        $paymentBatch->is_processed = 1;
        $paymentBatch->processed_by = auth()->user()->id ?? 0;
        $paymentBatch->processed_at = date('Y-m-d H:i:s');
        $paymentBatch->save();
    
        //process all payments in the batch
        $payments = Payment::where('payment_batch_id',$id)->where('is_processed',0)->get();
    
        foreach($payments as $payment){
            $payment->is_processed = 1;
            $payment->processed_by = auth()->user()->id ?? 0;
            $payment->processed_at = date('Y-m-d H:i:s');
            $payment->save();
        }

        //store activity
        Helpers::storeActivity('processed payment batch - '.$paymentBatch->name);

        //return success message if payment batch is processed
        toastr()->success('Payment Batch Processed Successfully');
        return redirect()->back();
    }

    //process payment
    public function processPayment($id)
    {
        //find payment
        $payment = Payment::find($id);
    
        //return error message if payment is not found
        if(!$payment){
            toastr()->error('Payment Not Found');
            return redirect()->back();
        }
    
        //check if payment is already processed
        if($payment->is_processed == 1){
            toastr()->error('Payment Already Processed');
            return redirect()->back();
        }
    
        //process payment
        $payment->is_processed = 1;
        $payment->processed_by = auth()->user()->id ?? 0;
        $payment->processed_at = date('Y-m-d H:i:s');
        $payment->save();

        //store activity
        Helpers::storeActivity('processed payment for '.$payment->beneficiary_name);
    
        //return success message if payment is processed
        toastr()->success('Payment Processed Successfully');
        return redirect()->back();
    }

    //delete unapproved payments
    public function deleteUnapprovedPayments(Request $request)
    {
        //validate request using validate request from helper class

        $validate = Helpers::validateRequest($request,[
            'payments' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return response()->json([
                'status' => false,
                'message' => implode(',',$validate),
            ]);
        }

        foreach($request->payments as $payment){
            //find payment
            $payment = Payment::find($payment);

            //return error message if payment is not found
            if(!$payment){
                toastr()->error('Payment Not Found');
                return response()->json([
                    'status' => false,
                    'message' => 'Payment Not Found',
                ]);
            }

            //check if payment is approved or processed
            if($payment->is_approved == 1 || $payment->is_processed == 1){
                toastr()->error('Payment Cannot Be Deleted');
                return response()->json([
                    'status' => false,
                    'message' => 'Payment Cannot Be Deleted',
                ]);
            }
            $batch_id = $payment->payment_batch_id;
            $type_id = $payment->type_id;
            //delete payment
            $delete = $payment->delete();

            //return success message if payment is deleted
            if($delete){
                //remove amount from payment batch total amount
                $batch = PaymentBatch::find($batch_id);
                $batch->total_amount = $batch->total_amount - $payment->amount;
                $batch->save();

                //update paid out status for loans and dividends
                if($payment->payment_type == 'loan'){
                    $loan = Loan::find($type_id);
                    $loan->paid_out = 0;
                    $loan->save();
                }
                if($payment->payment_type == 'dividend'){
                    $dividend = AnnualDividendDetail::find($type_id);
                    $dividend->paid_out = 0;
                    $dividend->save();
                }
            }
        }

        $batch = PaymentBatch::find($batch_id);

        //store activity
        Helpers::storeActivity('deleted unapproved payments in batch - '.$batch->name);

        //return success message if payment is deleted
        toastr()->success('Payments Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Payments Deleted Successfully',
        ]);
    }
        
    //search payments
    public function searchPayments(Request $request)
    {
        //get payments
        $payments = Payment::where('payment_batch_id', $request->batch)->get();

        //if payments does not exist, return empty table body
        if(count($payments) == 0){
            return response()->json([
                'data' => '<tr><td valign="top" colspan="7" class="dataTables_empty">No data available in table</td></tr>',
            ]);
        }

        //put payments in a table body
        $table_body = '';
        $i = 1;
        foreach($payments as $payment){
            $approved_by = User::find($payment->approved_by) ?? '';
            $date_approved = $payment->approved_at ? date('d M Y H:i:s',strtotime($payment->approved_at)):'';
            $processed_by = User::find($payment->processed_by) ?? '';
            $date_processed = $payment->processed_at ? date('d M Y H:i:s',strtotime($payment->processed_at)):'';
            //if the payment has been approved, disable the approve button and delete button
            $approve_button = '<a href="'.route('payment.approve', $payment->id).'" class="btn btn-success btn-sm">Approve</a>';
            $delete_button = '<a href="'.route('payment.delete', $payment->id).'" class="btn btn-danger btn-sm">Delete</a>';
            $reject_button = '';
            $process_button = '<a href="'.route('payment.process', $payment->id).'" class="btn btn-success btn-sm">Process</a>';

            //table date for button
            $button = '<td>'.$approve_button.$delete_button.'</td>';

            $status = 'unapproved';
            if($payment->is_approved){
                $approve_button = '';
                $delete_button = '';
                $reject_button = '<a href="'.route('payment.reject', $payment->id).'" class="btn btn-danger btn-sm">Reject</a>';
                $button = '';
                $status = 'approved';
            }

            if($payment->is_processed){
                $process_button = '';
                $button = '';
                $status = 'processed';
            }
            $table_body .= '<tr>
            <td>'.$i++.'</td>
                <td>'.$payment->beneficiary
                .'</td>
                <td>'.$payment->amount.'</td>
                <td>'.$payment->payment_type.'</td>
                <td>'.$payment->description.'</td>
                <td>'.$status.'</td>
                <td>'.$approved_by.'</td>
                <td>'.$date_approved.'</td>
                <td>'.$processed_by.'</td>
                <td>'.$date_processed.'</td>
                '.$button.$process_button.$reject_button.'
            </tr>';

        }

            return response()->json([
                'data' => $table_body,
            ]);

    }

    //search payment by batch, type, beneficiary name, bank, account number, date range
    public function searchPaymentsByFilter(Request $request)
    {
        //validate date range request using validate request from helper clas

        $validate = Helpers::validateRequest($request,[
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            toastr()->error('Date Range Required');
            return redirect()->back();
        }

        $pageTitle = 'Payment history';

        //create date range
        $date_from = date('Y-m-d H:i:s',strtotime($request->date_from.' 00:00:00'));
        $date_to = date('Y-m-d H:i:s',strtotime($request->date_to.' 23:59:59'));


        $conditions = '';
        
        //if other filters are selected, filter payments
        if($request->batch_name != ''){
            $conditions .= 'AND batch_name like "%'.$request->batch_name.'%"';
        }
        if($request->category != ''){
            $conditions .= ' AND payment_type = "'.$request->category.'"';
        }
        if($request->beneficiary_name != ''){
            $conditions .= ' AND beneficiary_name like "%'.$request->beneficiary_name.'%"';
        }
        if($request->bank != ''){
            $conditions .= ' AND bank = "'.$request->bank.'"';
        }
        if($request->account != ''){
            $conditions .= ' AND beneficiary_account_no = "'.$request->account.'"';
        }

        $payments = Payment::whereRaw('payments.is_processed = 1 AND payments.processed_at >= "'.$date_from.'" AND payments.processed_at <= "'.$date_to.'" '.$conditions)
        ->leftJoin('payment_batches','payment_batches.id','payments.payment_batch_id')
        ->select('payments.*','payment_batches.name as batch_name')
        ->get();

        //remove items that are not in the date range
        $array = [];

        
        foreach($payments as $payment){
            $processed_at = strtotime($payment->processed_at);
            if($processed_at >= strtotime($date_from) && $processed_at <= strtotime($date_to)){
                $array[] = $payment;
            }
        }

        $payments = $array;
        
        $search = true;

        return view('payment.table', compact('payments','search','pageTitle'));

    }

    //search processed batches by date range

    public function searchProcessedBatches(Request $request)
    {

        $validate = Helpers::validateRequest($request,[
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        //return error if validation fails
        if($validate != 'valid'){
            toastr()->error('Date Range Required');
            return redirect()->back();
        }

        $pageTitle = 'Processed Payment Batches';

        //create date range
        $date_from = date('Y-m-d H:i:s',strtotime($request->date_from.' 00:00:00'));
        $date_to = date('Y-m-d H:i:s',strtotime($request->date_to.' 23:59:59'));

        $payment_batches = PaymentBatch::whereRaw('is_processed = 1 AND processed_at >= "'.$date_from.'" AND processed_at <= "'.$date_to.'" ')->get();

        //remove items that are not in the date range
        $array = [];
        foreach($payment_batches as $payment_batch){
            $processed_at = strtotime($payment_batch->processed_at);
            if($processed_at >= strtotime($date_from) && $processed_at <= strtotime($date_to)){
                $array[] = $payment_batch;
            }
        }

        $batches = $array;
        
        $search = true;

        return view('payment.processed.batches', compact('batches','search','pageTitle'));

    }

    //export processed payment

    public function exportProcessedPayment(Request $request, $batch_id){

        $batch = PaymentBatch::find($batch_id);

        $payments = Payment::where('payment_batch_id',$batch_id)->where('is_processed',1)->get();

        $data = [];

        foreach($payments as $payment){
            $data[] = [
                'Beneficiary Name' => $payment->beneficiary_name,
                'Bank' => $payment->bank,
                'Beneficiary Account No' => $payment->beneficiary_account_no,
                'Amount' => $payment->amount,
                'Description' => $payment->description,
            ];
        }

        //add array keys as the first row
        array_unshift($data, array_keys($data[0]));

        $file_name = 'Processed'.$batch->name.'_'.date('d_m_Y_H_i_s').'.xlsx';

        return Excel::download(new dataExport($data), $file_name);

    }


}
