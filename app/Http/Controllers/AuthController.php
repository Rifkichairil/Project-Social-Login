<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function login()
    {
        return view('login');
    }

    // google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user =  Socialite::driver('google')->user();
        $this->_registerOrLoginUser($user);

        return redirect()->route('home');
    }

    // facebook Login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        return Socialite::driver('facebook')->user();
    }

    // github Login
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        return Socialite::driver('github')->user();
    }

    protected function _registerOrLoginUser($data)
    {
        $user = User::where('email', $data->email)->first();

        if ($user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
        }

        Auth::login($user);
    }
}
