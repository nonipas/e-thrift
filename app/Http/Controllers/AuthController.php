<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mockery\Generator\StringManipulation\Pass\Pass;
use App\Models\PasswordReset;
use App\Helpers\Helpers;
//laravel notify helper
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        //check if the database has any user with the role slug super-admin
        $users = User::all();
        if ($users->isEmpty()) {
            //create super admin
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@mail.com',
                'phone' => '08000000000',
                'role_id' => 0,
                'status' => 1,
                'password' => bcrypt('123456'),
            ]);
        }

        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            toastr()->error('Invalid login details');
            return redirect()->back();
        }

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }

    //reset password
    public function resetPassword($code)
    {
        $token = PasswordReset::where('token', $code)->first();

        if (!$token) {
            toastr()->error('Invalid token');
            return redirect()->route('login');
        }

        return view('reset_password', compact('token'));
    }

    public function storeResetPassword(Request $request)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            toastr()->error('User not found');
            return redirect()->back();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        //send email
        Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\PasswordResetSuccess());
        
        toastr()->success('Password reset successfully');
        return redirect()->route('login');
    }

    //verify email
    public function verifyEmail($code)
    {
        $user = User::where('email_verification_code', $code)->first();

        if (!$user) {
            toastr()->error('Invalid token');
            return redirect()->back();
        }

        $user->email_verified_at = now();
        $user->save();

        //send email
        Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\EmailVerified());

        return redirect()->route('login');
    }

    //verify reset password token

    public function verifyResetPasswordToken(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        //verify token
        $token = PasswordReset::where('token', $request->token)->first();

        if (!$token) {
            
            notify()->error('Invalid token');
        }

        if (!$user) {
            notify()->error('User not found');
        }

        return view('reset_password', compact('user', 'token'));
    }

    //send reset password link

    public function sendResetPasswordLink(Request $request)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'email' => 'required|email',
        ]);

        //report validation errors
        if ($validate != 'valid') {
            foreach ($validate as $error) {
                toastr()->error($error);
            }
            return redirect()->back();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            toastr()->error('User not found');
            return redirect()->back();
        }

        //create token
        $token = PasswordReset::create([
            'email' => $user->email,
            'token' => uniqid(),
        ]);

        //send email
        $user->sendPasswordResetNotification($token->token);

        toastr()->success('Password reset link sent to your email');
        return redirect()->route('login');
    }

}

