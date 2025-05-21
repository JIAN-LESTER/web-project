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

        if ($request->input('two_factor_code') == $user->two_factor_code && now()->lt($user->two_factor_expires_at)) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            Auth::login($user);

            Logs::create([
                'userID' => $user->userID,
                'action_type' => 'Successful 2FA login',
                'timestamp' => now(),
            ]);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', '2FA Verified Successfully. You are now logged in.');
            } elseif ($user->role === 'user') {
                return redirect()->route('chatbot')->with('success', '2FA Verified Successfully. You are now logged in.');
            }
        }

        Logs::create([
            'userID' => $user->userID,
            'action_type' => 'Failed 2FA login attempt',
            'timestamp' => now(),
        ]);

        session()->forget('2fa_user_id');

        return back()->withErrors(['two_factor_code' => 'Invalid or expired OTP. Please log in again.']);
    }

    public function resend(Request $request)
    {
        $user = User::find(session('2fa_user_id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['message' => 'Session expired. Please log in again.']);
        }

        // Generate new code and expiration
        $user->two_factor_code = rand(100000, 999999);
        $user->two_factor_expires_at = now()->addMinutes(5);
        $user->save();

        // Send the new code via email
        Mail::to($user->email)->send(new TwoFactorCodeMail($user));

        return back()->with('message', 'A new verification code has been sent to your email.');
    }
}
