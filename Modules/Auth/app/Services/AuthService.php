<?php

namespace Modules\Auth\App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
    public function __construct(protected User $user )
    {

    }


    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     */
    public function register($validatedData)
    {
        $user = $this->user::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'type' => $validatedData['type'],
        ]);
        if ($validatedData['type'] === 'owner') {

            $user->assignRole('owner');

        }
        $user->assignRole('user');
        return $user;

    }

    /**
     * Login the user and return an API token.
     *
     * @param array $credentials
     * @return string
     */
    public function login($credentials): array
    {
        $user = $this->user::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception(__('messages.invalid_credentials'));
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    /**
     * Send the password reset link.
     *
     * @param array $data
     * @return string
     */
    public function sendPasswordResetLink($data)
    {
        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new Exception(__('messages.unable_to_send_reset_link'));
        }

        return trans('auth.password_reset_link_sent');
    }

    /**
     * Reset the password.
     *
     * @param array $data
     * @return void
     */
    public function resetPassword($data)
    {
        $status = Password::reset($data, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw new Exception(__('messages.password_reset_failed'));
        }
    }

}
