<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\ImageHandler\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialiteProvider extends Controller
{

    public ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUpload){
        $this->imageUploadService = $imageUpload;
    }

    public function redirect($provider):RedirectResponse{
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider):RedirectResponse{

        $userSocialite = Socialite::driver($provider)->user();

 
        $user = User::updateOrCreate([
            'provider_id' => $userSocialite->id,
        ], [
            'name' => $userSocialite->getName(),
            'email' => $userSocialite->getEmail(),
            'avatar'=> $this->imageUploadService->saveImageFromUrl($userSocialite->getAvatar(),'socialite'),
            'provider'=>$provider,
            'provider_token' => $userSocialite->token,
            'email_verified_at'=>now()
        ]);

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
