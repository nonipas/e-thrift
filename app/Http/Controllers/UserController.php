<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Helpers\Helpers;

class UserController extends Controller
{
    //user list
    public function index()
    {
        $pageTitle = "Users";
        $users = User::all();
        return view('user.table', compact('pageTitle', 'users'));
    }

    //add user
    public function add()
    {
        $pageTitle = "Add new user";
        return view('user.form', compact('pageTitle'));
    }

    //store user
    public function store(Request $request)
    {

        //validate using helper class
        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //generate password with 6 character
        $password = '123456';

        //check if user exists
        $user = User::where('email', $request->email)->first();
        if ($user) {
            notify()->error('User already exists');
            return redirect()->back();
        }

        //create user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->email, //if username is not set, use email as username
            'email' => $request->email,
            'phone' => $request->phone ?? null,
            'role_id' => $request->role,
            'status' => 1,
            'password' => bcrypt($password),
        ]);

        //send email to user
        Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\NewUser($user));

        //store activity using helper class
        Helpers::storeActivity('User created '.$user->name);

        toastr()->success('User created successfully');
        return redirect()->route('user.index');
    }

    //edit user
    public function edit($id)
    {
        $pageTitle = "Edit user";
        $user = User::find($id);
        return view('user.form', compact('pageTitle', 'user'));
    }

    //update user
    public function update(Request $request, $id)
    {
        //validate using helper class

        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
            'role' => 'required',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //update user
        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone ?? null,
            'role_id' => $request->role,
        ]);

        //store activity using helper class
        Helpers::storeActivity('User updated '.$user->name);

        toastr()->success('User updated successfully');
        return redirect()->route('user.index');
    }

    //delete user
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        //store activity using helper class
        Helpers::storeActivity('User deleted '.$user->name);
        toastr()->success('User deleted successfully');
        return redirect()->route('user.index');
    }

    //change user status
    public function changeStatus($id)
    {
        $user = User::find($id);
        $user->update([
            'status' => !$user->status,
        ]);

        //store activity using helper class
        Helpers::storeActivity('User status changed for '.$user->name);

        toastr()->success('User status changed successfully');
        return redirect()->route('user.index');
    }

    //send reset user password link
    public function resetPassword(Request $request, $id){

        //send password reset link
        $user = User::find($id);
        Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\ResetPassword($user));

        //store activity using helper class
        Helpers::storeActivity('Password reset link sent to '.$user->name);

        toastr()->success('Password reset link sent successfully');
        return redirect()->route('user.index');
    }

    //update full profile
    public function updateProfile(Request $request)
    {

        //validate using helper class
        $validate = Helpers::validateRequest($request, [
            'name' => 'required',
            'phone' => 'numeric',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //update user
        $user = User::find(auth()->user()->id);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone ?? null,
        ]);

        //store activity using helper class
        Helpers::storeActivity('Profile updated');


        toastr()->success('Profile updated successfully');
        return redirect()->route('profile.index');
    }

    //update password
    public function updatePassword(Request $request)
    {
        //validate using helper class
        $validate = Helpers::validateRequest($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //check old password
        if (!password_verify($request->old_password, auth()->user()->password)) {
            toastr()->error('Old password is incorrect');
            return redirect()->route('profile.index');
        }

        //update password
        $user = User::find(auth()->user()->id);
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        //store activity using helper class
        Helpers::storeActivity('updated password');

        toastr()->success('Password updated successfully');
        return redirect()->route('profile.change_password');
    }


}
