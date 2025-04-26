<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Logs;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewDashboard()
    {
        return view('admin.dashboard');
    }

    public function viewKB()
    {
        return view('admin.knowledge_base');
    }

    public function viewReports()
    {
        return view('admin.reports_analytics');
    }

    public function viewLogs(Request $request)
    {
        $search = $request->get('search');

        $logs = Logs::query()
        ->with('user')
        ->when($search, function ($query, $search) {
            $query->whereHas('user', function($q) use ($search){
                $q->where('name', 'like',"%{$search}%");
            })
            ->orWhere('action_type', 'like', "%{$search}%")
                ->orWhere('timestamp', 'like', "%{$search}%");
        })->orderBy('timestamp', 'desc')
        ->paginate(12);

        return view('admin.logs', compact('logs', 'search'));
    }

    public function viewUsers(Request $request)
    {

        $search = $request->get('search');

        $users = User::with(['course', 'year'])
        ->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        })->paginate(12);

        $courses = Course::all();
        $years = Year::all();

        return view('admin.user_management', compact('users', 'search', 'years', 'courses'));
    }
    

    public function viewCharts()
    {
        return view('admin.charts');
    }

    public function viewForms()
    {
        return view('admin.forms');
    }
}
