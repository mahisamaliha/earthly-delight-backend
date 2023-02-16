<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email|exists:users,email',
            'password' => 'bail|required|min:2|max:20',
        ], ['email.exists' => 'No account found for this email']);

        $check = User::where('email', $request->email)->where('isActive', 1)->count();
        if ($check == 1) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'success' => true,
                    'msg' => 'You are logged in',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'Incorrect Password!!'
                ], 401);
            }
        } else {
            $passwordToken = rand(100000, 999999);
            User::where('email', $request->email)->update([
                'passwordToken' => $passwordToken,
            ]);
            $body = 'You have register for Camera World. Your OTP for Email verification is: ' . $passwordToken;

            \Mail::send('email-template', ['body' => $body], function ($message) use ($request) {
                $message->to($request->email)
                    ->from('noreply@info.com', 'Camera World')
                    ->subject('Email Verification');
            });

            return response()->json([
                'success' => false,
                'msg' => 'Your email is not verified!!  We have sent an OTP to your email. Submit your OTP to verify your account.'
            ], 402);
        }

    }
}
