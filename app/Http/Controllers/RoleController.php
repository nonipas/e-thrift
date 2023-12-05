<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\Helpers;

class RoleController extends Controller
{
    //store role
    public function store(Request $request)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
            'permissions' => 'required|array',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //check if role exists
        $role = \App\Models\Role::where('slug', Str::slug($request->name, '-'))->first();

        if ($role) {
            toastr()->error('Role already exists');
            return redirect()->back();
        }

        //convert permissions array to string, seperate each with comma
        $permissions = implode(',', $request->permissions);
        //create role
        $role = \App\Models\Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'permissions' => $permissions,
            'description' => $request->description ?? null,
            'status' => 1,
        ]);

        //attach permissions
        // $role->permissions()->attach($request->permissions);

        toastr()->success('Role created successfully');
        return redirect()->route('role.index');
    }

    //update role
    public function update(Request $request, $id)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
            'permissions' => 'required|array',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //convert permissions array to string, seperate each with comma
        $permissions = implode(',', $request->permissions);
        //create role
        $role = \App\Models\Role::find($id);
        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'permissions' => $permissions,
            'status' => 1,
        ]);

        toastr()->success('Role updated successfully');
        return redirect()->route('role.index');
    }

    //delete role
    public function delete($id)
    {
        //delete role
        \App\Models\Role::find($id)->delete();
        toastr()->success('Role deleted successfully');
        return redirect()->route('role.index');
    }

    //update role status
    public function updateStatus($id)
    {
        //update role status
        $role = \App\Models\Role::find($id);
        if ($role->status == 1) {
            $role->update([
                'status' => 0,
            ]);
        } else {
            $role->update([
                'status' => 1,
            ]);
        }

        toastr()->success('Role status updated successfully');
        return redirect()->route('role.index');
    }
}
