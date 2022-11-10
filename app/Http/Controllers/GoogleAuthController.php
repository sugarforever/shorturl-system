<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }
     
    public function callback() {
        $google_account = Socialite::driver('google')->user();
        if (! empty($google_account)) {
            $user = User::updateOrCreate([
                'google_id' => $google_account->id,
            ], [
                'name' => $google_account->name,
                'email' => $google_account->email,
                'password' => Hash::make(Str::random(8))
            ]);

            Auth::login($user);
 
            return redirect()->route('welcome');
        }
        return;
    }
}
