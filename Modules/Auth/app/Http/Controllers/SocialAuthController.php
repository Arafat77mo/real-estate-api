<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\App\Helpers\ResponseData;
use Modules\Auth\app\Services\SocialAuthService;
use Modules\Auth\App\Transformers\AuthResource;

class SocialAuthController extends Controller
{
    protected $socialAuthService;

    public function __construct(SocialAuthService $socialAuthService)
    {
        $this->socialAuthService = $socialAuthService;
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = $this->socialAuthService->getSocialUser($provider);

        $user = $this->socialAuthService->findOrCreateUser($provider, $socialUser);

        return redirect()->to('http://localhost:3000/login?token=' . urlencode($user['token']));

    }
}
