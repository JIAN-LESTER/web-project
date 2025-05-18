<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Course;
use App\Models\KnowledgeBase;
use App\Models\Logs;
use App\Models\Message;
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
        $filter = $request->query('filter', 'day');
    
        // Default to null for all-time (no date filtering)
        $start = null;
        $end = null;
    
        if ($filter === 'day') {
            $start = Carbon::today();
            $end = Carbon::today()->endOfDay();
        } elseif ($filter === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        } elseif ($filter === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } elseif ($filter === 'all') {
            // No date filtering, so start and end remain null
        }
    
        // Helper to add date filtering if $start and $end exist, and also filter sender = user
        $applyDateFilter = function ($query) use ($start, $end) {
            $query->where('sender', 'user');
            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
            return $query;
        };
    
        // Stats Cards (Filtered, sender = user)
        $totalMessages = $applyDateFilter(
            Message::query()
        )->count();
    
        $answeredMessages = $applyDateFilter(
            Message::whereNotNull('responded_at')
        )->count();
    
        $totalUsers = User::where('role', 'user')->count();
    
        // Category counts (Filtered, sender = user)
        $admissionMessages = $applyDateFilter(
            Message::where('categoryID', 1)
        )->count();
    
        $scholarshipMessages = $applyDateFilter(
            Message::where('categoryID', 2)
        )->count();
    
        $placementMessages = $applyDateFilter(
            Message::where('categoryID', 3)
        )->count();
    
        $generalMessages = $applyDateFilter(
            Message::where('categoryID', 4)
        )->count();
    
        // Most frequent category (Filtered, sender = user)
        $mostCategory = $applyDateFilter(
            Message::whereNotNull('categoryID')
        )
        ->select('categoryID', DB::raw('COUNT(*) as total'))
        ->groupBy('categoryID')
        ->orderByDesc('total')
        ->limit(1)
        ->pluck('categoryID')
        ->first();
    
        $mostCategoryName = Categories::find($mostCategory)?->category_name ?? 'Unknown';
    
        // Line Chart Data (Filtered sender = user)
        $labels = collect();
        $counts = collect();
    
        if ($filter === 'day') {
            for ($i = 23; $i >= 0; $i--) {
                $hourStart = Carbon::today()->addHours($i);
                $hourEnd = $hourStart->copy()->endOfHour();
            
                $labels->push($hourStart->format('H:00'));
            
                $count = Message::whereBetween('created_at', [$hourStart, $hourEnd])->count();
            
                $counts->push($count);
            }
        } elseif ($filter === 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $labels->push($date->format('M d'));
    
                $count = Message::where('sender', 'user')
                    ->whereDate('created_at', $date)
                    ->count();
    
                $counts->push($count);
            }
        } elseif ($filter === 'month') {
            for ($i = 3; $i >= 0; $i--) {
                $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
                $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
    
                $labels->push($monthStart->format('M Y'));
    
                $count = Message::where('sender', 'user')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();
    
                $counts->push($count);
            }
        } elseif ($filter === 'all') {
            $years = Message::where('sender', 'user')
                ->select(DB::raw('YEAR(created_at) as year'))
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('year');
    
            foreach ($years as $year) {
                $labels->push($year);
                $count = Message::where('sender', 'user')
                    ->whereYear('created_at', $year)
                    ->count();
                $counts->push($count);
            }
        }
    
        // Pie Chart (Filtered sender = user)
        $categoryDataQuery = Message::where('sender', 'user')->select('categoryID', DB::raw('COUNT(*) as total'))
            ->whereNotNull('categoryID');
        if ($start && $end) {
            $categoryDataQuery->whereBetween('created_at', [$start, $end]);
        }
        $categoryData = $categoryDataQuery->groupBy('categoryID')->get();
    
        $categoryLabels = collect();
        $categoryCounts = collect();
    
        foreach ($categoryData as $data) {
            $categoryName = Categories::find($data->categoryID)?->category_name ?? 'Unknown';
            $categoryLabels->push($categoryName);
            $categoryCounts->push($data->total);
        }
    
        // Recent Logs (Latest 5)
        $recentLogs = Logs::with('user')->latest('timestamp')->take(5)->get();
    
        return view('admin.dashboard', [
            'user' => $user,
            'totalMessages' => $totalMessages,
            'answeredMessages' => $answeredMessages,
            'totalUsers' => $totalUsers,
            'mostCategoryName' => $mostCategoryName,
            'admissionMessages' => $admissionMessages,
            'scholarshipMessages' => $scholarshipMessages,
            'placementMessages' => $placementMessages,
            'generalMessages' => $generalMessages,
            'labels' => $labels,
            'counts' => $counts,
            'filter' => $filter,
            'categoryLabels' => $categoryLabels,
            'categoryCounts' => $categoryCounts,
            'recentLogs' => $recentLogs,
        ]);
    }
    

    public function viewKB()
    {
        $documents = KnowledgeBase::with('category')->latest()->paginate(10);
        return view('admin.knowledge_base', compact('documents'));
    }



    public function viewLogs(Request $request)
    {
        $search = $request->get('search');

        $logs = Logs::query()
            ->with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
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
