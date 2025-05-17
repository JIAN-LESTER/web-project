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
    
        $start = now();
        $end = now();
    
        if ($filter === 'day') {
            $start = Carbon::today();
            $end = Carbon::today()->endOfDay();
        } elseif ($filter === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        } elseif ($filter === 'month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }
    
        // Stats Cards (Filtered)
        $totalMessages = Message::where('sender', 'user')
            ->whereBetween('created_at', [$start, $end])
            ->count();
    
        $answeredMessages = Message::where('sender', 'user')
            ->whereNotNull('responded_at')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $totalUsers = User::where('role', 'user')->count();
    
        // Category counts (Filtered)
        $admissionMessages = Message::where('categoryID', 1)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    
        $scholarshipMessages = Message::where('categoryID', 2)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    
        $placementMessages = Message::where('categoryID', 3)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    
        $generalMessages = Message::where('categoryID', 4)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    
        // Most frequent category (Filtered)
        $mostCategory = Message::whereNotNull('categoryID')
            ->where('sender', 'user')
            ->whereBetween('created_at', [$start, $end])
            ->select('categoryID', DB::raw('COUNT(*) as total'))
            ->groupBy('categoryID')
            ->orderByDesc('total')
            ->limit(1)
            ->pluck('categoryID')
            ->first();
    
        $mostCategoryName = Categories::find($mostCategory)?->category_name ?? 'Unknown';
    
        // Line Chart Data
        $labels = collect();
        $counts = collect();
    
        if ($filter === 'day') {
            for ($i = 23; $i >= 0; $i--) {
                $hour = Carbon::now()->subHours($i);
                $labels->push($hour->format('H:00'));
    
                $count = Message::whereDate('created_at', $hour->toDateString())
                    ->whereRaw('HOUR(created_at) = ?', [$hour->hour])
                    ->count();
    
                $counts->push($count);
            }
        } elseif ($filter === 'week') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $labels->push($date->format('M d'));
    
                $count = Message::whereDate('created_at', $date)->count();
    
                $counts->push($count);
            }
        } elseif ($filter === 'month') {
            for ($i = 3; $i >= 0; $i--) {
                $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
                $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();
    
                $labels->push($monthStart->format('M Y'));
    
                $count = Message::whereBetween('created_at', [$monthStart, $monthEnd])->count();
    
                $counts->push($count);
            }
        }
    
        // Pie Chart (Filtered)
        $categoryData = Message::select('categoryID', DB::raw('COUNT(*) as total'))
            ->whereNotNull('categoryID')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('categoryID')
            ->get();
    
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
