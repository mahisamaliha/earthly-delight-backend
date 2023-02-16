<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function authUser(){
        try {
            \Log::info('I am in Auth try');

            $user_id = Auth::user()->id;
            $data = User::where('id', $user_id)->with('customer')->first();
            $formattedData = [];
            return $data;
            // unset($data['created_at']);
            // unset($data['updated_at']);
            // unset($data['adminDiscount']);
            // unset($data['appDiscount']);
            // unset($data['openingQuantity']);
            // unset($data['openingUnitPrice']);

            // $data['address'] = $data->customer->address;
            // $data['areaId'] = $data->customer->areaId;
            // $data['balance'] = $data->customer->balance;
            // $data['cityId'] = $data->cityId->cityId;
            // $data['facebook'] = $data->customer->facebook;
            // $data['instagram'] = $data->customer->instagram;
            // $data['postCode'] = $data->customer->postCode;
            // $data['status'] = $data->customer->status;
            // $data['zone'] = $data->customer->zone;
            // $data['zoneId'] = $data->customer->zoneId;
            // unset($data['customer']);
            // array_push($formattedData, $data);

            // \Log::info($formattedData);
            // return response()->json([
            //     'success'=> true,
            //     'data'=>$formattedData,
            // ],200);
        } catch (\Throwable $th) {
            \Log::info('I am in Auth catch');

            return response()->json([
                'msg'=>'Auth not found'
            ], 401);
        }

    }
    function register(Request $request)
    {
        //validate Request
        // $request->validate(
        //     [
        //         'name' => 'bail|required|regex:/^[a-zA-z. ]+$/',
        //         'email' => [
        //             'required',
        //             'max:50',
        //             'email',
        //             'unique:users,email',
        //         ],
        //         'contact' => 'required',

        //         'password' => ['required',
        //            'min:8',
        //            'max:20',
        //            'regex:/^.*((?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*<+_-])).*$/'],
        //         // 'password_confirmation' => 'required',
        //     ],
        //     [
        //         'name.regex' => 'Only Characters are allowed!!',
        //         'password.regex' => '1 upper, 1 lower, 1 digit, 1 Special Character'
        //     ]
        // );

        $passwordToken = rand(100000, 999999);
        $token_expired_at = now();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'username' => $request->username,
            'password' => $request->password,
            'passwordToken' => $passwordToken,
            'token_expired_at' => $token_expired_at,
        ]);

        $customer = Customer::create([
            'userId'=> $user->id,
            'customerName'=> $request->name,
            'contact' => $request->contact,
            'username' => $request->username,
        ]);
        // $body = 'You have register for Camera World. Your OTP for Email verification is: ' . $passwordToken;

        // \Mail::send('email-template', ['body' => $body], function ($message) use ($request) {
        //     $message->to($request->email)
        //         ->from('noreply@info.com', 'Camera World')
        //         ->subject('Email Verification');
        // });

        return response()->json(['msg' => 'Registered successfully. We have sent an OTP to your email. Submit your OTP to verify your account.', 'status' => $user, 'customer'=>$customer], 200);
    }

    //email verification
    public function verifyEmail(Request $request){
        $request->validate([
            'token' => 'required',
            'contact' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('contact', $request->contact)->where('passwordToken', $request->token)->first();

        if(!$user){
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Token!!'
            ], 401);
        }
        $time_now = now();
        $previous_time = now()->subMinutes(5);
        // if(User::where('email', $request->email)->whereBetween('token_expired_at', [$previous_time, $time_now])->count()==0){
        //     // User::where('email', $request->email)->update([
        //     //     'passwordToken' => null,
        //     //     'token_expired_at' => null,
        //     // ]);
        //     return response()->json([
        //         'success' => false,
        //         'msg' => 'Token Expired!!'
        //     ], 402);
        // }
        User::where('contact', $request->contact)->update([
            'isActive' => 1,
            // 'isVerifiedCode' => null,
        ]);

        $token = auth()->attempt($request->only('contact', 'password'));

        return response()->json([
            'user' => $user,
            'token'=>$token,
        ],200);



        // return response()->json(['msg' => 'Email verified successfully!!', 'status' => 'success'], 200);

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'contact' => 'bail|required|exists:users,contact',
            'password' => 'bail|required|min:2|max:20',
        ], ['contact.exists' => 'No account found for this Number']);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }


        $input = $request->all();
        $data = User::select('id', 'contact', 'password')->where('contact', $input['contact'])->first();

        //The makeVisible method returns the model instance
        $data->makeVisible('password')->toArray();

        $checkUser = Hash::check($input['password'], $data->password);
        if (!$checkUser) {
            return response()->json(['msg'=>'Invalid credentials'], 401);
        }
        $user = User::where('contact', $request->contact)->where('isActive', 1)->first();
        if ($user) {
            if($token = auth()->attempt($validator->validated())){
                return response()->json([
                    'user' => $user,
                    'token'=>$token,
                ],200);
            }
            else{
                return response()->json(['msg'=>'Invalid credentials'], 401);
            }

        } else {
            $passwordToken = rand(100000, 999999);
            User::where('contact', $request->contact)->update([
                'passwordToken' => $passwordToken,
            ]);
            $body = 'You have register for Camera World. Your OTP for Email verification is: ' . $passwordToken;

            // \Mail::send('email-template', ['body' => $body], function ($message) use ($request) {
            //     $message->to($request->email)
            //         ->from('noreply@info.com', 'Camera World')
            //         ->subject('Email Verification');
            // });

            return response()->json([
                'success' => false,
                'msg' => 'Your email is not verified!!  We have sent an OTP to your email. Submit your OTP to verify your account.'
            ], 402);
        }

    }



    public function refesh(){
        return $this->respondWithToken(auth()->refresh);
    }

    public function logout(){
        auth()->logout();
        return response()->json([
            'msg' => 'Logged Out'
        ]);
    }
}
