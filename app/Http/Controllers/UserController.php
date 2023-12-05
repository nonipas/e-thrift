<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

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
        //validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
        ]);

        //generate password with 6 character
        $password = '123456';

        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? null,
            'role_id' => $request->role,
            'status' => 1,
            'password' => bcrypt($password),
        ]);

        //send email to user
        Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\NewUser($user));

        notify()->success('User created successfully');
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
        //validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);

        //update user
        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? null,
            'role_id' => $request->role,
        ]);

        notify()->success('User updated successfully');
        return redirect()->route('user.index');
    }

    //delete user
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        notify()->success('User deleted successfully');
        return redirect()->route('user.index');
    }

    //change user status
    public function changeStatus($id)
    {
        $user = User::find($id);
        $user->update([
            'status' => !$user->status,
        ]);

        notify()->success('User status updated successfully');
        return redirect()->route('user.index');
    }

    //send reset user password link
    public function resetPassword(Request $request, $id){

        //send password reset link
        $user = User::find($id);
        Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\ResetPassword($user));

        notify()->success('Password reset link sent successfully');
        return redirect()->route('user.index');
    }

    //update full profile
    public function updateProfile(Request $request)
    {
        //validate request
        $request->validate([
            'name' => 'required',
            'phone' => 'numeric',
        ]);

        //update user
        $user = User::find(auth()->user()->id);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone ?? null,
        ]);

        notify()->success('Profile updated successfully');
        return redirect()->route('profile.index');
    }

    //update password
    public function updatePassword(Request $request)
    {
        //validate request
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        //check old password
        if (!password_verify($request->old_password, auth()->user()->password)) {
            notify()->error('Old password does not match');
            return redirect()->route('profile.index');
        }

        //update password
        $user = User::find(auth()->user()->id);
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        notify()->success('Password updated successfully');
        return redirect()->route('profile.change_password');
    }


}
