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
            'phone' => 'required|numeric|min:10|max:255|unique'.User::class
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
}
