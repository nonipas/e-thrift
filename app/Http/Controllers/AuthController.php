<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mockery\Generator\StringManipulation\Pass\Pass;
use App\Helpers\Helpers;
use App\Models\PasswordResetToken;
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
        $users = User::where('role_id', 0)->get();
        if ($users->isEmpty()) {
            //create super admin
            $user = User::create([
                'name' => 'Super Admin',
                'username' => 'admin',
                'email' => $request->email,
                'phone' => '08000000000',
                'role_id' => 0,
                'status' => 1,
                'password' => bcrypt($request->password),
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
        $token = PasswordResetToken::where('token', $code)->first();

        if (!$token) {
            toastr()->error('Invalid Password reset token');
            return redirect()->route('login');
        }

        return view('reset_password', compact('token'));
    }

    public function storeResetPassword(Request $request)
    {
        //validate request using validate method from helper class
        $validate = Helpers::validateRequest($request, [
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
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

        //check if user has a token
        $token = PasswordResetToken::where('email', $user->email)->first();

        if ($token) {
            //delete token via email
            PasswordResetToken::where('email', $user->email)->delete();
        }

        //create token
        $token = PasswordResetToken::create([
            'email' => $user->email,
            'token' => uniqid(),
        ]);

        $data = [
            'token' => $token->token,
            'name' => $user->name,
            'email' => $user->email,
            'url' => route('reset-password', $token->token),
        ];

        //send email
        $user->notify(new \App\Notifications\ResetPassword($data));

        //check if email was sent
        $mail = Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\ResetPassword($data));

        if ($mail) {
            toastr()->success('Password reset link sent to your email');
            //return route with data
            return redirect()->route('recover-password')->with('data', $data);
        }

        toastr()->error('Error sending password reset link');

        return redirect()->back()->with('data', $data);
    }

}

