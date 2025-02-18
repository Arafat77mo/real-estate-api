<?php

namespace Modules\Auth\app\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    /**
     * الحصول على بيانات المستخدم من المزود
     *
     * @param string $provider
     * @return SocialiteUser
     */
    public function getSocialUser(string $provider)
    {
        return Socialite::driver($provider)->stateless()->user();
    }

    /**
     * البحث عن المستخدم أو إنشاؤه باستخدام بيانات تسجيل الدخول الاجتماعي
     *
     * @param string $provider
     * @param SocialiteUser $socialUser
     * @return array
     */
    public function findOrCreateUser(string $provider, SocialiteUser $socialUser): array
    {
        // البحث عن المستخدم بناءً على البريد الإلكتروني
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'        => $socialUser->getName() ?? $socialUser->getNickname(),
                'email'       => $socialUser->getEmail(),
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
                'type' => 'user',
                'password'    => bcrypt(Str::random(16)),
            ]);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user
        ];

    }
}
