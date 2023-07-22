<?php

namespace App\Http\Controllers\Api\Auth\SocialAuth;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function googleLogin(){
        return Socialite::driver('google')->redirect();
    }
    public function googleCallback(){
        // $user = Socialite::driver('google')->user();


        return 'test';
    }
}