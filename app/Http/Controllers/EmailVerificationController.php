<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Logs;
use Illuminate\Support\Facades\Request as RequestFacade;


use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if(!$user)
        {
            return redirect('/login')->with('error', 'Invalid Verification Token.');
        }

        // Logs::create([
        //     'user_id' => $user->id,
        //     'login_id' => $user->last_name,
        //     'action' => "Registering",
        //     'timestamp' => now(),
        //     'ip_address' => RequestFacade::ip(),
        // ]);

        $user->is_verified = true;
        $user->verification_token = null;
        $user->save();

        Logs::create([
            'userID' => $user->userID,
            'action_type' => "Email Verified",
            'timestamp' => now(),
        ]);

        return redirect('/login')->with('success', 'Email Verified successfully. You can now log in.');
    }
}
