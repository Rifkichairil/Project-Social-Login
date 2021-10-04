<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    //
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProvideCallback($provider)
    {

        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back();
        }

        // dd($user);
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth()->login($authUser, true);

        return redirect()->route('home');
    }

    public function findOrCreateUser($socialUser, $provider)
    {
        $socialAccount = SocialAccount::where('fk_provider_id', $socialUser->getId())
            ->where('provider_name', $provider)
            ->first();
        if ($socialAccount) {

            return $socialAccount->user;
        } else {
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                ]);
            }

            $user->socialAccounts()->create([
                'fk_provider_id' => $socialUser->getId(),
                'provider_name' => $provider
            ]);

            return $user;
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
