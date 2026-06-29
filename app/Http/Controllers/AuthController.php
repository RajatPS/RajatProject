<?php

namespace App\Http\Controllers;


use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;


class AuthController extends Controller
{

// For Google signup
    public function signupredirect(Request $request)
    {
        // Fixed: Use config('app.url') instead of hardcoded URL
        return Socialite::driver('google')
        ->redirectUrl(config('app.url') . '/auth/googlesignup/callback')
        ->redirect();
    }

    public function signupcallback()
    {

        try {
            // Fixed: Use config('app.url') instead of hardcoded URL
            $user = Socialite::driver('google')
                ->redirectUrl(config('app.url') . '/auth/googlesignup/callback')
                ->user();

            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                // Fixed: Check if account is active before login
                if (strtolower($existingUser->account_status) !== 'active') {
                    return redirect('/seller/sellerLogin')->withErrors(['error' => 'Your account has been disabled.']);
                }
                
                Auth::login($existingUser);
                return redirect('/seller/sellerDashboard');
            } 
            else {
                $newUser = User::create([
                    'email' => $user->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'seller',
                    'account_status' => 'active', // Fixed: Set active status for new user
                ]);

                //i want to use twilo to send otp to the user phone number for verification before login here

                Auth::login($newUser);
                
                return redirect('/seller/sellerDashboard');
            }
        } 
        catch (\Throwable $e) {
            dd([
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'session' => session()->all(),
                'request_state' => request()->get('state'),
                'session_state' => session('state')
            ]);
        }
    }


    // For multiple providers Faceboook

    public function fredirect($provider)
    {
        // This will redirect to Google or Facebook based on the URL
        return Socialite::driver($provider)->redirect();
    }

    public function fcallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::updateOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'name' => $socialUser->getName(),
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                'password' => bcrypt(Str::random(16)),
            ]);

            Auth::login($user);

            return redirect('/seller/dashboard')->with('success', 'Logged in via ' . ucfirst($provider));
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Authentication failed. Please try again.');
        }
    }
}
