<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\Course;
use App\Models\Logs;
use App\Models\Year;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'year_id' => 'nullable|exists:years,yearID',
            'course_id' => 'nullable|exists:courses,courseID',
        ]);

        // Default values
        $yearID = $validated['year_id'] ?? null;
        $courseID = $validated['course_id'] ?? null;

        // If yearID is null (or 0), force courseID to null
        if ($yearID === null || $yearID == 0) {
            $courseID = null;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user',
            'user_status' => 'active',
            'yearID' => $yearID,
            'courseID' => $courseID,
            'verification_token' => Str::random(64),
            'avatar' => 'avatars/default.png',
        ]);
        Logs::create([
            'userID' => $user->userID,
            'action_type' => 'Registered own account. Email verification sent.',
            'timestamp' => now(),
        ]);


        Mail::to($user->email)->send(new VerifyEmail($user));

        return redirect()->route('registration.success');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereRaw('LOWER(email) = ?', [strtolower($request->email)])->first();
        if (!$user) {
            return back()->with('error', 'No account found')->withInput();
        }

        if ($this->isAccountLocked($user)) {
            $secondsLeft = now()->diffInSeconds($user->lockout_time);
            $minutesLeft = floor($secondsLeft / 60);
            $secondsRemainder = $secondsLeft % 60;

            return back()
                ->with('account_locked', true)
                ->with('lockout_timer', "$minutesLeft minutes and $secondsRemainder seconds")
                ->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            $this->incrementFailedAttempts($user);
            $remainingAttempts = 5 - $user->failed_attempts;

            return back()
                ->with('error', 'Incorrect email or password.')
                ->with('failed_attempts', $user->failed_attempts)
                ->with('remaining_attempts', $remainingAttempts)
                ->withInput();
        }
        $this->resetFailedAttempts($user);

        if ($user->is_verified == 0) {
            return back()->with('not_verified', 'Your email is not yet verified. Please check your email and try again.')->withInput();
        }

        // Generate and send a two-factor authentication code
        $user->two_factor_code = rand(100000, 999999);
        $user->two_factor_expires_at = now()->addMinutes(5);
        $user->save();

        Logs::create([
            'userID' => $user->userID,
            'action_type' => 'Attempted to login. 2FA code sent.',
            'timestamp' => now(),
        ]);


        Mail::to($user->email)->send(new TwoFactorCodeMail($user));

        session(['2fa_user_id' => $user->userID]);

        return redirect()->route('2fa.verify.form')->with('message', 'A 2FA code has been sent to your email.');
    }

    protected function isAccountLocked($user)
    {

        if ($user->failed_attempts >= 5) {
            if ($user->lockout_time && now()->lt($user->lockout_time)) {
                Logs::create([
                    'userID' => $user->userID,
                    'action_type' => "Account locked due to multiple failed login attempts",
                    'timestamp' => now(),
                ]);
                return true;
            }
            $this->resetFailedAttempts($user);
        }

        return false;
    }


    protected function incrementFailedAttempts($user)
    {
        $user->failed_attempts++;
        if ($user->failed_attempts >= 5) {
            // Lock the account for 15 minutes after 5 failed attempts
            $user->lockout_time = now()->addMinutes(5);
            $user->save();
        } else {
            $user->save();
        }
    }

    protected function resetFailedAttempts($user)
    {
        $user->failed_attempts = 0;
        $user->lockout_time = null; // Reset lockout time
        $user->save();
    }

    public function logout(Request $request)
    {

        $user = Auth::user();
        if ($user) {
            Logs::create([
                'userID' => $user->userID,
                'action_type' => "Logged out",
                'timestamp' => now(),
            ]);
        }

        $conversationID = session('current_conversation_id');
        if ($conversationID) {
            \App\Models\Conversation::where('conversationID', $conversationID)->update(['conversation_status' => 'ended']);
        }
        
        Session::forget('current_conversation_id');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        $years = Year::all();
        $courses = Course::all();
        return view('auth.register', compact('years', 'courses'));
    }


    public function resendTwoFactorCode(Request $request)
    {
        // Check if we have a user ID in the session
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        $userId = session('2fa_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found. Please login again.');
        }

        // Generate a new code and update expiration time
        $user->two_factor_code = rand(100000, 999999);
        $user->two_factor_expires_at = now()->addMinutes(3); // Match the 3-minute timer in the UI
        $user->save();

        // Send the new code via email
        Mail::to($user->email)->send(new TwoFactorCodeMail($user));

        Logs::create([
            'userID' => $user->userID,
            'action_type' => "Resent 2FA code",
            'timestamp' => now(),
        ]);


        return redirect()->route('2fa.verify.form')
            ->with('message', 'A new verification code has been sent to your email.');
    }
}
