<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use App\Models\Logs;

class TwoFactorAuthController extends Controller
{
    public function verifyForm()
    {
        return view('auth.two-factor');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer|digits:6',
        ]);

        $user = User::find(session('2fa_user_id'));

        if (!$user) {
            return back()->withErrors(['two_factor_code' => 'Session expired. Please log in again.']);
        }

        if ($request->input('two_factor_code') == $user->two_factor_code && now()->lt($user->two_factor_expires_at)) 
        {
            $user->update([
                'two_factor_code' => null, 
                'two_factor_expires_at' => null,
            ]);

            Auth::login($user);

            // Logs::create([
            //     'user_id' => $user->id,
            //     'login_id' => $user->last_name,
            //     'action' => 'Successful Login with 2FA',
            //     'timestamp' => now(),
            //     'ip_address' => $request->ip(), // Fixed request IP retrieval
            // ]);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', '2FA Verified Successfully. You are now logged in.');
            } elseif ($user->role === 'user') {
                return redirect()->route('user.chatbot')->with('success', '2FA Verified Successfully. You are now logged in.');
            }
        }

        // Log failed 2FA attempt
        // Logs::create([
        //     'user_id' => $user->id,
        //     'login_id' => $user->last_name,
        //     'action' => 'Failed 2FA attempt (Invalid or expired OTP)',
        //     'timestamp' => now(),
        //     'ip_address' => $request->ip(), // Fixed request IP retrieval
        // ]);

        session()->forget('2fa_user_id');

        return back()->withErrors(['two_factor_code' => 'Invalid or expired OTP. Please log in again.']);
    }
}
