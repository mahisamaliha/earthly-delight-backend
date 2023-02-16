<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function sendResetPassOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $reset_pass_code = rand(100000, 999999);
        $token_expired_at = now();
        User::where('email', $request->email)->update([
            'reset_pass_code' => $reset_pass_code,
            'token_expired_at' => $token_expired_at,
        ]);

        //$action_link = redirect('/reset')->route( ['token' => $token, 'email' => $request->email]);

        $body = 'We have received a request to reset the password for Camera World account associated with ' . $request->email . '. Your code for reset password: ' . $reset_pass_code;

        \Mail::send('email-template', ['body' => $body], function ($message) use ($request) {
            $message->to($request->email)
                ->from('noreply@info.com', 'Camera World')
                ->subject('Reset Password');
        });

        return response()->json(['msg' => 'We have sent an code to your email.', 'status' => $request->email], 200);
    }

    public function submitResetPassOtp(Request $request)
    {
        $request->validate([
            'reset_pass_code' => 'required',
        ]);

        $time_now = now();
        \Log::info('Time Now');
        \Log::info($time_now);

        $previous_time = now()->subMinutes(5);
        \Log::info($previous_time);

        if(User::where('email', $request->email)->where('reset_pass_code', $request->reset_pass_code)->count()==0){
            User::where('email', $request->email)->update([
                'reset_pass_code' => null,
                'token_expired_at' => null,
            ]);
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Token!!'
            ], 401);
        }
        if(User::where('email', $request->email)->whereBetween('token_expired_at', [$previous_time, $time_now])->count()==0){
            User::where('email', $request->email)->update([
                'reset_pass_code' => null,
                'token_expired_at' => null,
            ]);
            return response()->json([
                'success' => false,
                'msg' => 'Token Expired!!'
            ], 402);
        }
        else {
            return response()->json(['msg' => 'Success!!', 'status' => 'success'], 200);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:2|max:20',
            'password_confirmation' => 'required',
        ]);
        $check = User::where('email', $request->email)->where('reset_pass_code', $request->reset_pass_code)->count();
        if ($check == 1) {
            User::where('email', $request->email)->update([
                'password' => \Hash::make($request->password),
                'reset_pass_code' => null,
                'token_expired_at' => null,
            ]);
            return response()->json(['msg' => 'Password updated successfully', 'status' => 'success'], 200);
        } else {
            return response()->json(['msg' => 'Invalid Code', 'status' => 'error'], 401);
        }
    }
}
