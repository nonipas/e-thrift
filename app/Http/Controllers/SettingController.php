<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use App\Helpers\Helpers;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PaymentCategory;
use App\Models\Setting;
use App\Models\Member;
use Hamcrest\Type\IsString;


class SettingController extends Controller
{
    //add/update settings in key value pair
    public function update(Request $request)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'settings' => 'required|array',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }
        

        //loop through settings

        foreach ($request->settings as $key => $value) {
            //check if key exists
            $setting = Setting::where('key', $key)->first();

            //if key exists update value
            if ($setting) {
                $setting->update([
                    'value' => $value,
                ]);
            } else {
                //else create new setting
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }



        $message = Helpers::notifyArray('success', 'Settings updated successfully');
        return redirect()->back()->with($message);
    }

    //store permission
    public function storePermission(Request $request)
    {
        //validate request using validate method from helper class

        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //make slug
        $slug = Str::slug($request->name, '-');

        //check if permission exists

        $permission = Permission::where('slug', $slug)->first();

        if ($permission) {
            toastr()->error('Permission already exists');
            return redirect()->back();
        }
        //create permission
        Permission::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => 1,
        ]);

        toastr()->success('Permission created successfully');
        return redirect()->route('setting.permission');
    }

    //update permission
    public function updatePermission(Request $request, $id)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //make slug
        $slug = Str::slug($request->name, '-');
        //update permission
        Permission::find($id)->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        toastr()->success('Permission updated successfully');
        return redirect()->route('setting.permission');
    }

    //delete permission
    public function deletePermission($id)
    {
        //delete permission
        Permission::find($id)->delete();

        toastr()->success('Permission deleted successfully');
        return redirect()->route('setting.permission');
    }

    //change permission status
    public function changePermissionStatus($id)
    {
        //get permission
        $permission = Permission::find($id);
        //change status
        if ($permission->status == 1) {
            $permission->update([
                'status' => 0,
            ]);
        } else {
            $permission->update([
                'status' => 1,
            ]);
        }

        toastr()->success('Permission status changed successfully');
        return redirect()->route('setting.permission');
    }

    //store payment category
    public function storePaymentCategory(Request $request)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        } 

        //check if payment category exists
        $paymentCategory = PaymentCategory::where('name', $request->name)->first();

        if ($paymentCategory) {
            toastr()->error('Payment category already exists');
            return redirect()->back();
        }

        //make slug
        $slug = Str::slug($request->name, '-');
        //create payment category
        PaymentCategory::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        toastr()->success('Payment category created successfully');
        return redirect()->route('setting.payment_category');
    }

    //update payment category
    public function updatePaymentCategory(Request $request, $id)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //make slug
        $slug = Str::slug($request->name, '-');
        //update payment category
        PaymentCategory::find($id)->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        toastr()->success('Payment category updated successfully');
        return redirect()->route('setting.payment_category');
    }

    //delete payment category
    public function deletePaymentCategory($id)
    {
        //delete payment category
        PaymentCategory::find($id)->delete();

        toastr()->success('Payment category deleted successfully');
        return redirect()->route('setting.payment_category');
    }
}
