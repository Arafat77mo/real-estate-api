<?php

namespace Modules\Auth\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Auth\App\Helpers\ResponseData;
use Modules\Auth\App\Http\Requests\CreateAuthRequest;
use Illuminate\Support\Facades\Log;
use Modules\Auth\App\Services\AuthService;
use Modules\Auth\App\Transformers\AuthResource;

class AuthController extends Controller
{

    public function __construct( protected AuthService $authService)
    {
    }

    /**
     * Register a new user.
     *
     * @param CreateAuthRequest $request
     * @return JsonResponse
     */
    public function register(CreateAuthRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());
            return ResponseData::send(
                __('messages.success'),
                __('messages.user_registered'),
                new AuthResource($user)
            );
        } catch (\Exception $e) {
            // Log the error for further debugging
            Log::error('Registration error: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.registration_failed') // Use a more generic error message
            );
        }
    }

    /**
     * Login the user.
     *
     * @param CreateAuthRequest $request
     * @return JsonResponse
     */
    public function login(CreateAuthRequest $request): JsonResponse
    {
        try {
            $authData = $this->authService->login($request->validated());

            return ResponseData::send(
                __('messages.success'),
                __('messages.user_logged_in'),
                [
                    'token' => $authData['token'],
                    'user' => new AuthResource($authData['user']),
                ]
            );
        } catch (\Exception $e) {
            // Log the error for further debugging
            Log::error('Login error: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.login_failed') // Use a more generic error message
            );
        }
    }

    /**
     * Send password reset link.
     *
     * @param CreateAuthRequest $request
     * @return JsonResponse
     */
    public function sendPasswordResetLink(CreateAuthRequest $request): JsonResponse
    {
        try {
            $message = $this->authService->sendPasswordResetLink($request->validated());
            return ResponseData::send(
                __('messages.success'),
                __('messages.password_reset_link_sent')
            );
        } catch (\Exception $e) {
            // Log the error for further debugging
            Log::error('Password reset error: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.password_reset_failed') // Use a more generic error message
            );
        }
    }

    /**
     * Reset the user password.
     *
     * @param CreateAuthRequest $request
     * @return JsonResponse
     */
    public function resetPassword(CreateAuthRequest $request): JsonResponse
    {
        try {
            $this->authService->resetPassword($request->validated());
            return ResponseData::send(
                __('messages.success'),
                __('messages.password_reset')
            );
        } catch (\Exception $e) {
            // Log the error for further debugging
            Log::error('Password reset error: ' . $e->getMessage());
            return ResponseData::send(
                __('messages.error'),
                __('messages.password_reset_failed') // Use a more generic error message
            );
        }
    }
}
