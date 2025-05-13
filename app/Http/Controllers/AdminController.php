<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\KnowledgeBase;
use App\Models\Logs;
use App\Models\User;
use App\Models\Year;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{

public function viewDashboard(Request $request)
{
        $user = Auth::user();
    $filter = $request->query('filter', 'day'); // default is 'day'
    
    $labels = collect();
    $counts = collect();

    if ($filter === 'day') {
        // Past 24 hours
        for ($i = 23; $i >= 0; $i--) {
            $hour = Carbon::now()->subHours($i);
            $labels->push($hour->format('H:00'));
    
            $count = DB::table('messages')
                ->whereDate('created_at', $hour->toDateString())
                ->whereRaw('HOUR(created_at) = ?', [$hour->hour])
                ->count();
    
            $counts->push($count);
        }
    
    
    } elseif ($filter === 'week') {
        // Past 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels->push($date->format('M d'));

            $count = DB::table('messages')
                ->whereDate('created_at', $date)
                ->count();

            $counts->push($count);
        }
    } elseif ($filter === 'month') {
        // Past 4 weeks
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $labels->push('Week ' . $startOfWeek->format('W'));

            $count = DB::table('messages')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $counts->push($count);
        }
    }

    return view('admin.dashboard', [
        'labels' => $labels,
        'counts' => $counts,
        'filter' => $filter,
        'user' => $user
    ]);
}

    

public function viewKB()
{
    $documents = KnowledgeBase::with('category')->latest()->paginate(10);
    return view('admin.knowledge_base', compact('documents'));
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
