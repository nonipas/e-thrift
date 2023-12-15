<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\Helpers;
use App\Models\Member;
use App\Models\Month;

class GeneralController extends Controller
{
    //get account name from helpers class
    public function getAccountName(Request $request){
        $account_name = Helpers::getMemberName($request->account_number, $request->bank);
        if(!$account_name){
            return response()->json(['status'=>false,'message' => 'Account number was not resolved']);
        }
        return response()->json(['status'=>true,'account_name' => $account_name]);
    }

    //get members by search term from helpers class
    public function getMembers(Request $request){
        $members = Helpers::searchMembers($request->q);

        //paginate members
        // $page = $request->page;
        // $perPage = 30;
        // $offset = ($page - 1) * $perPage;
        // $members = array_slice($members, $offset, $perPage);


        if(count($members) == 0){
            return response()->json(['total_count'=>0,'items' => []]);
        }
        return response()->json([
            'items' => $members,
            'total_count' => count($members),
            'incomplete_results' => true
        ]);
    }

    //get member details
    public function getMemberDetails(Request $request){
        
        $id = $request->id;

        $member = Member::find($id);

        if(!$member){
            return response()->json(['not found'],404);
        }
        return response()->json([
            'status'=>1,
            'name'=>$member->name,
            'account_number'=>$member->account_number,
            'bank'=>$member->bank,
            'phone'=>$member->phone,
            'email'=>$member->email,
            'department'=>$member->department,
        ]);
    }

    //save months of the year to database
    public function saveMonths(){
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        foreach($months as $month){
            Month::create([
                'name' => $month,
                'code' => Str::substr($month, 0, 3),
            ]);
        }
        return true;
    }
}
