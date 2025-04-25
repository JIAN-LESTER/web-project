<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\Course;
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
            'course_id' => 'nullable|exists:courses,courseID',
            'year_id' => 'nullable|exists:years,yearID',
        ]);

        // Create the user with the actual foreign keys
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'user',
            // 'user_status' => 'active',
            'courseID' => $validated['course_id'] ?? null, // Use the course_id from the form
            'yearID' => $validated['year_id'] ?? null, // Use the year_id from the form
            'verification_token' => Str::random(64),
            'avatar' => 'avatars/default.png',
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

        if (!$user || !Hash::check($request->password, $user->password)) {
            $this->incrementFailedAttempts($user);
            if ($this->isAccountLocked($user)) {
                return back()->with('error', 'Your account is locked due to too many failed login attempts. Please try again later.');
            }
            return back()->with('error', 'Incorrect email or password. ' . $user->failed_attempts . '/5 failed attempts.');
        }


        $this->resetFailedAttempts($user);


        if ($user->is_verified == 0) {
            return back()->with('not_verified', 'Your email is not yet verified. Please check your email and try again.')->withInput();
        }

        // Generate and send a two-factor authentication code
        $user->two_factor_code = rand(100000, 999999);
        $user->two_factor_expires_at = now()->addMinutes(5);
        $user->save();



        Mail::to($user->email)->send(new TwoFactorCodeMail($user));

        session(['2fa_user_id' => $user->userID]);

        return redirect()->route('2fa.verify.form')->with('message', 'A 2FA code has been sent to your email.');
    }

    protected function isAccountLocked($user)
    {
        if ($user->failed_attempts >= 5 && $user->lockout_time && now()->lt($user->lockout_time)) {
            return true; // Account is locked
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

    // Reset failed attempts on successful login
    protected function resetFailedAttempts($user)
    {
        $user->failed_attempts = 0;
        $user->lockout_time = null; // Reset lockout time
        $user->save();
    }

    public function logout(Request $request)
    {
        // Clearing the session data
        if (Session::has('user')) {
            Session::forget('user');
            return redirect('/login')->with('success', 'You have been logged out.');
        }

        return redirect('/login')->with('success', 'You have been logged out.');
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

    public function authenticated(Request $request, $user)
    {
        // If the user is not verified, log them out and show an error
        if (!$user->is_verified) {
            Auth::logout();
            return redirect('/login')->with('error', 'Please verify your email before logging in.');
        }

        return redirect('/users.index');
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

        return redirect()->route('2fa.verify.form')
            ->with('message', 'A new verification code has been sent to your email.');
    }
}
