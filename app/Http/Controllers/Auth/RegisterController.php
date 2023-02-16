<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    function registerUser(Request $request)
    {
        //validate Request
        $request->validate(
            [
                'name' => 'bail|required|regex:/^[a-zA-z. ]+$/',
                'email' => [
                    'required',
                    'max:50',
                    'email',
                    'unique:users,email',
                ],
                'contact' => 'required',

                'password' => ['required',
                   'min:8',
                   'max:20',
                   'regex:/^.*((?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*<+_-])).*$/',
                   'confirmed'],
                'password_confirmation' => 'required',
            ],
            [
                'name.regex' => 'Only Characters are allowed!!',
                'password.regex' => '1 upper, 1 lower, 1 digit, 1 Special Character'
            ]
        );

        $passwordToken = rand(100000, 999999);
        $token_expired_at = now();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'username' => $request->contact,
            'password' => $request->password,
            'passwordToken' => $passwordToken,
            'token_expired_at' => $token_expired_at,
        ]);

        // $body = 'You have register for Camera World. Your OTP for Email verification is: ' . $passwordToken;

        // \Mail::send('email-template', ['body' => $body], function ($message) use ($request) {
        //     $message->to($request->email)
        //         ->from('noreply@info.com', 'Camera World')
        //         ->subject('Email Verification');
        // });

        return response()->json(['msg' => 'Registered successfully. We have sent an OTP to your email. Submit your OTP to verify your account.', 'status' => $user], 201);
    }

    //email verification
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);
        $time_now = now();
        \Log::info('Time Now');
        \Log::info($time_now);

        $previous_time = now()->subMinutes(5);
        \Log::info($previous_time);

        if(User::where('email', $request->email)->where('passwordToken', $request->otp)->count()==0){
            User::where('email', $request->email)->update([
                'passwordToken' => null,
                'token_expired_at' => null,
            ]);
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Token!!'
            ], 401);
        }
        if(User::where('email', $request->email)->whereBetween('token_expired_at', [$previous_time, $time_now])->count()==0){
            User::where('email', $request->email)->update([
                'isVerifiedCode' => null,
                'token_expired_at' => null,
            ]);
            return response()->json([
                'success' => false,
                'msg' => 'Token Expired!!'
            ], 402);
        }

        else {
            User::where('email', $request->email)->update([
                'isActive' => 1,
                'isVerifiedCode' => null,
            ]);
            return response()->json(['msg' => 'Email verified successfully!!', 'status' => 'success'], 200);
        }
    }

}
