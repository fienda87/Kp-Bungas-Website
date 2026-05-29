<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Only allow existing admin/registered users to login
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user && $user->hasRole('admin')) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);

                Auth::login($user);

                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            return redirect()->route('login')->withErrors(['email' => 'Hanya akun Admin yang terdaftar yang dapat masuk menggunakan Google.']);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Terjadi kesalahan saat login menggunakan Google.']);
        }
    }
}
