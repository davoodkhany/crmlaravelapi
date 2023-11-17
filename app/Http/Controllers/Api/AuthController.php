<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Models\User;
use App\Notifications\NotificationForgetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create User
     */

    public function createUser(AuthRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        if ($user) {
            $token = $user->createToken('create-token')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            return response()->json(['message' => 'Failed to create user.'], 500);
        }
    }

    public function LoginUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255,exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('token')->plainTextToken;
                return response()->json(['user' => $user, 'token' => $token], 200);

            } else {

                return response()->json(['errors' => ['password' => ['پسورد شما اشتباه است']]], 422);
            }
        } else {
            return response()->json(['errors' => ['email' => ['این ایمیل وجود ندارد']]], 422);

        }

    }

    // Email Verify Checker
    public function isEmailExists(Request $request)
    {
        $EmailExists = User::whereEmail($request->email)->first();
        if ($EmailExists) {
            return response()->json(['errors' => ['email' => ['ایمیل قبلا انتخاب شده است.']]], 422);
        }

    }

    public function SignOut(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json('Successful tokens Delete', 200);
    }

    public function ForgetPassword(Request $request)
    {

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if(!$user){
            return response()->json(['message' => 'ایمیل وارد شده معتبر نمی‌باشد']);
        }

        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $token = Password::getRepository()->create($user);
            $user->notify(new NotificationForgetPassword($token));
        }

        return response()->json(['message' => 'لینک بازیابی رمز عبور ارسال شد.']);

    }

}
