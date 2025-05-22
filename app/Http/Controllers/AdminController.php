<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Category;
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
    public function viewKB(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category');

    $documents = KnowledgeBase::with('category')
        ->when($search, function ($query, $search) {
            $query->where('kb_title', 'like', "%{$search}%");
        })
        ->when($category, function ($query, $category) {
            $query->where('categoryID', $category);
        })
        ->latest()
        ->paginate(10);

    $categories = Categories::all();

    return view('admin.knowledge_base', compact('documents', 'search', 'category', 'categories'));
    
}

    



public function viewLogs(Request $request)
{
    $search = $request->get('search');
    $filter = $request->get('filter', 'all'); // user, action, or all
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');

    $logs = Logs::query()->with('user');

    if ($search) {
        if ($filter === 'user') {
            $logs->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        } elseif ($filter === 'action') {
            $logs->where('action_type', 'like', "%{$search}%");
        } else { // all
            $logs->where(function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('action_type', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%");
            });
        }
    }

    if ($startDate) {
        $logs->whereDate('created_at', '>=', $startDate);
    }

    if ($endDate) {
        $logs->whereDate('created_at', '<=', $endDate);
    }

    $logs = $logs->orderBy('created_at', 'desc')->paginate(12)->appends($request->query());

    return view('admin.logs', compact('logs', 'search', 'filter'));
}


    public function viewInquiryLogs(Request $request)
    {
        $search = $request->get('search');
    
        $logs = Logs::query()
        ->with(['user', 'message'])
        ->whereNotNull('messageID')
        ->when($search, function ($query, $search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('message', function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%");
            })
            ->orWhere('created_at', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);
    
        return view('admin.inquiry_logs', compact('logs', 'search'));
    }
    

    public function viewUsers(Request $request)
    {
        $search = $request->get('search');
        $roles = $request->get('roles', []);
        $statuses = $request->get('statuses', []);
        $year = $request->get('year');
        $course = $request->get('course');
    
        $users = User::with(['course', 'year'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->when(!empty($roles), function ($query) use ($roles) {
                return $query->whereIn('role', $roles);
            })
            ->when(!empty($statuses), function ($query) use ($statuses) {
                return $query->whereIn('status', $statuses);
            })
            ->when($year, function ($query) use ($year) {
                return $query->where('year_level', $year);
            })
            ->when($course, function ($query) use ($course) {
                return $query->where('course_name', $course);
            })
            ->paginate(12)
            ->appends($request->query()); // retain filters in pagination links
    
        $courses = Course::all();
        $years = Year::all();
    
        return view('admin.user_management', compact(
            'users',
            'search',
            'years',
            'courses',
            'roles',
            'statuses',
            'year',
            'course'
        ));
    }
    

}
