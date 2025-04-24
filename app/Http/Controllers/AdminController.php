<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewDashboard()
    {
        return view('admin.dashboard');
    }

    public function viewKB(){
        return view('admin.knowledge_base');
    }
    
    public function viewReports(){
        return view('admin.reports_analytics');
    }

    public function viewLogs(){
        return view('admin.logs');
    }

    public function viewUsers(){

        $users = User::all();

        return view('admin.user_management', compact('users'));
    }

    public function viewCharts(){
        return view('admin.charts');
    }

    public function viewForms(){
        return view('admin.forms');
    }
}
