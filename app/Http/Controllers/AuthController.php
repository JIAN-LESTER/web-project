<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Logs;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

use App\Mail\TwoFactorCodeMail;



use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'courseID' => 'nullable|exists:courses,courseID',
            'yearID' => 'nullable|exists:years,yearID',
           
         

           
        ]);
    
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
            'user_status' => 'active',
            'courseID' => $validated['courseID'] ?? null,
            'yearID' => $validated['yearID'] ?? null,
            'verification_token' => Str::random(64),
            'avatar' => 'avatars/default.png',
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user));

        // Logs::create([
        //     'user_id' => $user->id,
        //     'login_id' => $user->last_name,
        //     'action' => "Sending Verification",
        //     'timestamp' => now(),
        //     'ip_address' => RequestFacade::ip(),
        // ]);

    
        return redirect('login')->with('success', 'A verification link has been sent to your email.');
    }
    

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereRaw('LOWER(email) = ?', [strtolower($request->email)])->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Incorrect email or password.');
        }
    
        if ($user->is_verified == 0) {
            return back()->with('not_verified', 'Your email is not yet verified. Please check your email and try again.')->withInput();
        }
    
     
        if ($user) {
          
            $user->two_factor_code = rand(100000, 999999);
            $user->two_factor_expires_at = now()->addMinutes(3);
            $user->save();
    
          
            Mail::to($user->email)->send(new TwoFactorCodeMail($user));
    
            session(['2fa_user_id' => $user->id]);
            Logs::create([
                'user_id' => $user->id,
                'login_id' => $user->last_name,
                'action' => 'Attempted login (2FA required)',
                'timestamp' => now(),
                'ip_address' => RequestFacade::ip(),
            ]);

            Auth::logout();
       
            return redirect()->route('2fa.verify.form')->with('message', 'A 2FA code has been sent to your email.');
        }
    
        return back()->with('error', 'Something went wrong. Please try again.');
    }
    
    

    public function logout(Request $request){
        if (Session::has('user')) {
            Logs::create([
                'user_id' => Session::get('user.id'),
                'login_id' => Session::get('user.last_name'),
                'action' => 'Logged out',
                'timestamp' => now(),
                'ip_address' => RequestFacade::ip(),
            ]);

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
        return view('auth.register');
    }

    public function authenticated(Request $request, $user)  
    {
        if (!$user->is_verified) {
            Auth::logout();
            return redirect('/login')->with('error', 'Please verify your email
           before logging in.');
            }
            return redirect('/users.index');
           
    }


}
