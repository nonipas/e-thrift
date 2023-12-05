<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Bank;
use App\Models\Member;
use App\Models\Activity;


class Helpers
{
    //get user role
    public static function getUserRole($id)
    {
        $role = Role::find($id);
        return $role->name;
    }

    //get role permissions
    public static function getRolePermissions($id)
    {
        $role = Role::find($id);
        return $role->permissions;
    }

    //get permission name
    public static function getPermissionName($id)
    {
        $permission = Permission::find($id);
        return $permission->name;
    }

    //check if user has permission to access a route
    public static function hasPermission($permission)
    {
        $user = auth()->user();
        $role = Role::find($user->role_id);
        $permissions = explode(',', $role->permissions);
        if (in_array($permission, $permissions)) {
            return true;
        } else {
            return false;
        }
    }

    //create notify array for laravel notify
    public static function notifyArray($type, $message)
    {
        $res = array(
            'type' => $type,
            'message' => $message,
        );
    }

    //display message in toastr
    public static function getToastr($message)
    {
        $type = 'success';
        if ($message['type'] == 'error') {
            $type = 'danger';
        }
        if ($message['type']== 'errors') {
            $type = 'danger';
            foreach ($message['message'] as $error) {
                return toastr()->error($error);
            }
        }
        return toastr()->$type($message['message']);
    }

    //validate form request and return errors
    public static function validateRequest($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = $validator->errors()->all();
            
            return $message;
        }
        return 'valid';
    }

    //did validation pass
    public static function isValid($validate)
    {
        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }
    }

    //get banks from paystack api
    public static function getBanks()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "cache-control: no-cache",
                "content-type: application/json",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        //save banks to database
        foreach ($response['data'] as $bank) {
            //if bank code already exists, skip
            $cbank = Bank::where('code', $bank['code'])->first();
            if ($cbank) {
                continue;
            }
            Bank::create([
                'name' => $bank['name'],
                'code' => $bank['code'],
            ]);
        }

        return true;
    }

    //store activity
    public static function storeActivity($activity)
    {
        Activity::create([
            'user_id' => auth()->user()->id ?? 1,
            'action' => $activity,
        ]);
    }

    //get bank name
    public static function getBankName($code)
    {
        $bank = Bank::where('code', $code)->first();
        return $bank->name;
    }

    //get bank code
    public static function getBankCode($name)
    {
        $bank = Bank::where('name', $name)->first();
        return $bank->code;
    }

    //get member name by bank account number and bank code using paystack api
    public static function getMemberName($account_number, $bank_code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=" . $account_number . "&bank_code=" . $bank_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "cache-control: no-cache",
                "content-type: application/json",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        if (!$response['status']) {
            return false;
        }

        return $response['data']['account_name'];
    }

    //search for member by any field
    public static function searchMembers($q){
        $members = Member::where('members.name', 'LIKE', '%'.$q.'%')
        ->orWhere('email', 'LIKE', '%'.$q.'%')
        ->orWhere('phone', 'LIKE', '%'.$q.'%')
        ->orWhere('account_number', 'LIKE', '%'.$q.'%')
        ->orWhere('banks.name', 'LIKE', '%'.$q.'%')
        ->join('banks', 'banks.code', '=', 'members.bank')
        ->select('members.*', 'banks.name as bank_name')
        ->get();
        //return a combination of name and account number
        $items = [];
        foreach($members as $member){
            $items['full_name'] = strtoupper($member->name).' - '.$member->account_number;
        }

        return $members;
    }

    //get mo

}
