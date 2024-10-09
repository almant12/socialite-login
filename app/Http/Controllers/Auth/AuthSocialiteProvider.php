<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteProvider extends Controller
{


    public function redirect($provider):RedirectResponse{
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider):RedirectResponse{

        $userSocialite = Socialite::driver($provider)->user();

 
        $user = User::updateOrCreate([
            'provider_id' => $userSocialite->id,
        ], [
            'name' => $userSocialite->name,
            'email' => $userSocialite->email,
            'provider'=>$provider,
            'provider_token' => $userSocialite->token,
            'email_verified_at'=>now()
        ]);

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
