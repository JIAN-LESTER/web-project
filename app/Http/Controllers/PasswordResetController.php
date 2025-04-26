<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        session(['email' => $request->email]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            // Fetch the user to log the action
            $user = User::where('email', $request->email)->first();

            if ($user) {
                Logs::create([
                    'userID' => $user->userID,
                    'action_type' => "Sent a password reset link to {$request->email}",
                    'timestamp' => now(),
                ]);
            }

            return back()->with('success', 'Password reset link sent to your email.');
        }

        return back()->withErrors(['email' => 'Failed to send reset link.']);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                // Log inside the callback because $user is available here
                Logs::create([
                    'userID' => $user->userID,
                    'action_type' => "Reset password for {$request->email}",
                    'timestamp' => now(),
                ]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password has been reset successfully.')
            : back()->withErrors(['email' => 'Failed to reset password.']);
    }
}
