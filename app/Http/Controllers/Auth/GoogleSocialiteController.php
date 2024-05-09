<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleSocialiteController extends Controller
{
    public function redirectToAuth(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('google')
                         ->stateless()
                         ->redirect()
                         ->getTargetUrl(),
        ]);
    }
    
    public function handleAuthCallback(): JsonResponse
    {
        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        // $userExist = User::where('google_id', $socialiteUser->getId())->first();

        // if($userExist != null){
        //     return response()->json([
        //         'user' => $userExist,
        //         'access_token' => $socialiteUser->token,
        //         'token_type' => 'Bearer',
        //         'refresh_token' => $socialiteUser->refreshToken
        //     ]);
        // }

        /** @var User $user */
        $user = User::query()
            ->firstOrCreate(
                [
                    'email' => $socialiteUser->getEmail(),
                ],
                [
                    'email_verified_at' => now(),
                    'name' => $socialiteUser->getName(),
                    'google_id' => $socialiteUser->getId(),
                    'google_token' => $socialiteUser->token,
                    'google_refresh_token' => $socialiteUser->refreshToken,
                    'avatar' => $socialiteUser->getAvatar(),
                ]
            );

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('google_token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}