<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginVerification;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    // TODO maybe add ensureIsNotRateLimited logic but idk
    public function submit(Request $request): \Illuminate\Http\JsonResponse {
        // validate (request) the phone number

        $request->validate([
            //|unique:'.User::class
            'phone' => 'required|numeric|min:10'
        ]);

        // find or create a user model
        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if (!$user) {
            return response()->json([
                'message' => 'Could not process user with that phone number'
            ],401);
        }
        // send a user temporary code
        $user->notify(new LoginVerification());
        // return response

        return response()->json(['message' => 'Text message notification sent']);
    }

    public function verify(Request $request) {
        //validate the verification code
        $request->validate([
            //|unique:'.User::class
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|integer|between:111111,999999'
        ]);
        //find user
        $user = User::where('phone',$request->phone)->where('login_code',$request->login_code)->first();
        //verify the user code with one sent
        //return response if code is right return auth token if is massage of it's invalid
        if ($user) {
            $user->update([
                'login_code' => null
            ]);

            return $user->createToken($request->login_code)->plainTextToken;
        }

        return response()->json(['message' => 'Invalid verification code.'],401);

    }
}
