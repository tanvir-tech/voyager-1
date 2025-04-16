<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class SocialAuthController extends Controller
{
    
    public function redirectToGoogle(){
        
        return Socialite::driver('google')->redirect();
    
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'password' => null, // dummy password = bcrypt(uniqid())
            ]
        );

        Auth::login($user);
        return redirect()->intended('/dashboard');
    }

}
