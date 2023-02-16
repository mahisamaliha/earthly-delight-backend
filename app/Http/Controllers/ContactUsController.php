<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactUsController extends Controller
{
    function contactMessage(Request $request)
    {
        //validate Request
        $request->validate(
            [
                'name' => 'bail|required|regex:/^[a-zA-z. ]+$/',
                'email' => [
                    'required',
                    'max:50',
                    'email',
                ],
                'phone' => 'bail|required|regex:/(\+88)?0 ?1[3-9]\d{2}-?\d{6}/',
                'subject' => 'required',
                'message' => 'required'
            ],
            [
                'name.regex' => 'Only Characters are allowed!!',
                'phone.regex' => 'Enter a valid phone number'
            ]
        );

        $message = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json(['msg' => 'Message Sent', 'status' => $message], 201);
    }
}
