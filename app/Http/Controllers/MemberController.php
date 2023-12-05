<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\Helpers;
use App\Models\Member;

class MemberController extends Controller
{
    //store member
    public function store(Request $request){
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request,[
            'name' => 'required',
            'email' => 'email|unique:members,email',
            'phone' => 'numeric',
            'bank' => 'required',
            'account_number' => 'required|numeric|unique:members,account_number',
        ]);

        //report validation errors
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //create member
        $member = Member::create([
            'name' => $request->name,
            'employment_id' => $request->employment_id ?? null,
            'email' => $request->email ?? null,
            'phone' => $request->phone ?? null,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
            'department' => $request->department ?? null,
            'status' => 1,
        ]);

        Helpers::storeActivity('created member - '.$member->name);

        toastr()->success('Member created successfully');

        return redirect()->route('member.index');
    }

    //update member
    public function update(Request $request, $id){
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request,[
            'name' => 'required',
            'email' => 'email|unique:members,email,'.$id,
            'phone' => 'numeric',
            'bank' => 'required',
            'account_number' => 'required|numeric|unique:members,account_number,'.$id,
        ]);

        //report validation errors
        if($validate != 'valid'){
            foreach($validate as $error){
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //update member
        $member = Member::find($id);
        $member->update([
            'name' => $request->name,
            'employment_id' => $request->employment_id ?? null,
            'email' => $request->email ?? null,
            'phone' => $request->phone ?? null,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
            'department' => $request->department ?? null,
        ]);

        Helpers::storeActivity('updated member - '.$member->name);

        toastr()->success('Member updated successfully');
        return redirect()->route('member.index');
    }

    //update member status
    public function updateStatus(Request $request, $id){
        //update member
        $member = Member::find($id);
        $member->update([
            'status' => !$member->status,
        ]);

        Helpers::storeActivity('updated member status - '.$member->name);

        toastr()->success('status updated successfully');
        return redirect()->route('member.index');
    }

}
