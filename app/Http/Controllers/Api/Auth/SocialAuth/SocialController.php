<?php

namespace App\Http\Controllers\Api\Auth\SocialAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    public function googleLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token], 200);

        } else {

            $user = User::create([
                'name' => $request['family_name'],
                'email' => $request['email'],
                'password' => Str::password(12),
               
            ]);
            $user->markEmailAsVerified();

            if ($user) {
                $token = $user->createToken('create-token')->plainTextToken;
                return response()->json(['user' => $user, 'token' => $token], 200);
            } else {
                return response()->json(['message' => 'Failed to create user.'], 500);
            }
        }

    }

}
