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
        $user   = Auth::user();
        $filter = $request->query('filter', 'day');
    
        $start = $end = null;
    
        switch ($filter) {
            case 'day':
                $start = Carbon::today();
                $end   = $start->copy()->endOfDay();
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                break;
            case 'all':
            default:
                // No date filtering
                break;
        }
    
        $applyDateFilter = function ($query) use ($start, $end) {
            $query->where('sender', 'user');
            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
            return $query;
        };
    
        $totalMessages     = $applyDateFilter(Message::query())->count();
        $answeredMessages  = $applyDateFilter(Message::whereNotNull('responded_at'))->count();
        $totalUsers        = User::where('role', 'user')->count();
    
        $admissionMessages   = $applyDateFilter(Message::where('categoryID', 1))->count();
        $scholarshipMessages = $applyDateFilter(Message::where('categoryID', 2))->count();
        $placementMessages   = $applyDateFilter(Message::where('categoryID', 3))->count();
        $generalMessages     = $applyDateFilter(Message::where('categoryID', 4))->count();
    
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
    
        // Chart Data
        $labels = collect();
        $counts = collect();
    
        if ($filter === 'day') {
            $periodStart = $start;
            for ($i = 0; $i < 24; $i++) {
                $hourStart = $periodStart->copy()->addHours($i);
                $hourEnd = $hourStart->copy()->endOfHour();
    
                $labels->push($hourStart->format('H:00'));
    
                $count = Message::where('sender', 'user')
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->count();
    
                $counts->push($count);
            }
        } elseif ($filter === 'week') {
            $periodStart = $start;
            for ($i = 0; $i < 7; $i++) {
                $day = $periodStart->copy()->addDays($i);
    
                $labels->push($day->format('M d'));
    
                $count = Message::where('sender', 'user')
                    ->whereBetween('created_at', [
                        $day->copy()->startOfDay(),
                        $day->copy()->endOfDay()
                    ])
                    ->count();
    
                $counts->push($count);
            }
        } elseif ($filter === 'month') {
            $periodStart = $start;
            $monthsInRange = $start->diffInMonths($end) + 1;
    
            for ($i = 0; $i < $monthsInRange; $i++) {
                $monthStart = $periodStart->copy()->addMonths($i)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();
    
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
    
        // Message breakdown by category
        $categoryQuery = Message::where('sender', 'user')
            ->whereNotNull('categoryID');
    
        if ($start && $end) {
            $categoryQuery->whereBetween('created_at', [$start, $end]);
        }
    
        $categoryData = $categoryQuery
            ->select('categoryID', DB::raw('COUNT(*) as total'))
            ->groupBy('categoryID')
            ->get();
    
        $categoryLabels = collect();
        $categoryCounts = collect();
    
        foreach ($categoryData as $data) {
            $categoryName = Categories::find($data->categoryID)?->category_name ?? 'Unknown';
            $categoryLabels->push($categoryName);
            $categoryCounts->push($data->total);
        }
    
        // Recent logs
        $recentLogs = Logs::with('user')
            ->latest('created_at')
            ->take(5)
            ->get();
    
        return view('admin.dashboard', [
            'user'               => $user,
            'totalMessages'      => $totalMessages,
            'answeredMessages'   => $answeredMessages,
            'totalUsers'         => $totalUsers,
            'mostCategoryName'   => $mostCategoryName,
            'admissionMessages'  => $admissionMessages,
            'scholarshipMessages'=> $scholarshipMessages,
            'placementMessages'  => $placementMessages,
            'generalMessages'    => $generalMessages,
            'labels'             => $labels,
            'counts'             => $counts,
            'filter'             => $filter,
            'categoryLabels'     => $categoryLabels,
            'categoryCounts'     => $categoryCounts,
            'recentLogs'         => $recentLogs,
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
                    ->orWhere('created_at', 'like', "%{$search}%");
            })->orderBy('created_at', 'desc')
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
