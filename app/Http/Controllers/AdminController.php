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

    // ------------------------------------------------------------------
    // 1️⃣  Time-zone setup
    // ------------------------------------------------------------------
    $appTz = 'Asia/Manila';          // local timezone for labels
    $dbTz  = 'UTC';                  // timestamps in DB are stored in UTC

    // ------------------------------------------------------------------
    // 2️⃣  Date-range definitions (all in local tz first)
    // ------------------------------------------------------------------
    $start = $end = null;

    switch ($filter) {
        case 'day':
            $start = Carbon::today($appTz);
            $end   = $start->copy()->endOfDay();
            break;
        case 'week':
            $start = Carbon::now($appTz)->startOfWeek();
            $end   = Carbon::now($appTz)->endOfWeek();
            break;
        case 'month':
            $start = Carbon::now($appTz)->startOfMonth();
            $end   = Carbon::now($appTz)->endOfMonth();
            break;
        case 'all':
        default:
            // $start and $end stay null (no filtering)
            break;
    }

    // Convert to UTC for DB queries
    $startUtc = $start ? $start->copy()->timezone($dbTz) : null;
    $endUtc   = $end   ? $end->copy()->timezone($dbTz)   : null;

    // ------------------------------------------------------------------
    // 3️⃣  Helper closure to apply date filter + sender restriction
    // ------------------------------------------------------------------
    $applyDateFilter = function ($query) use ($startUtc, $endUtc) {
        $query->where('sender', 'user');
        if ($startUtc && $endUtc) {
            $query->whereBetween('created_at', [$startUtc, $endUtc]);
        }
        return $query;
    };

    // ------------------------------------------------------------------
    // 4️⃣  Stats cards
    // ------------------------------------------------------------------
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

    // ------------------------------------------------------------------
    // 5️⃣  Line-chart labels + counts
    // ------------------------------------------------------------------
    $labels = collect();
    $counts = collect();

    if ($filter === 'day') {
        for ($i = 0; $i < 24; $i++) {
            $hourStartLocal = Carbon::today($appTz)->addHours($i);
            $hourEndLocal   = $hourStartLocal->copy()->endOfHour();

            $labels->push($hourStartLocal->format('H:00'));

            $count = Message::whereBetween('created_at', [
                $hourStartLocal->copy()->timezone($dbTz),
                $hourEndLocal->copy()->timezone($dbTz)
            ])->where('sender', 'user')->count();

            $counts->push($count);
        }
    } elseif ($filter === 'week') {
        for ($i = 6; $i >= 0; $i--) {
            $dateLocal = Carbon::today($appTz)->subDays($i);
            $labels->push($dateLocal->format('M d'));

            $count = Message::where('sender', 'user')
                ->whereBetween('created_at', [
                    $dateLocal->copy()->startOfDay()->timezone($dbTz),
                    $dateLocal->copy()->endOfDay()->timezone($dbTz)
                ])->count();

            $counts->push($count);
        }
    } elseif ($filter === 'month') {
        for ($i = 3; $i >= 0; $i--) {
            $monthStartLocal = Carbon::now($appTz)->subMonths($i)->startOfMonth();
            $monthEndLocal   = $monthStartLocal->copy()->endOfMonth();

            $labels->push($monthStartLocal->format('M Y'));

            $count = Message::where('sender', 'user')
                ->whereBetween('created_at', [
                    $monthStartLocal->copy()->timezone($dbTz),
                    $monthEndLocal->copy()->timezone($dbTz)
                ])->count();

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

    // ------------------------------------------------------------------
    // 6️⃣  Pie-chart data
    // ------------------------------------------------------------------
    $categoryQuery = Message::where('sender', 'user')
        ->whereNotNull('categoryID');

    if ($startUtc && $endUtc) {
        $categoryQuery->whereBetween('created_at', [$startUtc, $endUtc]);
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

    // ------------------------------------------------------------------
    // 7️⃣  Recent logs
    // ------------------------------------------------------------------
    $recentLogs = Logs::with('user')
        ->latest('created_at')
        ->take(5)
        ->get();

    // ------------------------------------------------------------------
    // 8️⃣  Render view
    // ------------------------------------------------------------------
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
