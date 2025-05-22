<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Faq;
use App\Models\Logs;
use App\Models\Message;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $firstFaq = Faq::where('question', 'like', '%how do i start%')->first();

        $otherFaqs = Faq::where('faqID', '!=', optional($firstFaq)->id)
            ->inRandomOrder()
            ->take(5)
            ->get();

        // Combine into one collection
        $faqs = collect([$firstFaq])->filter()->merge($otherFaqs);

        return view('user.chatbot', compact('faqs'));
    }
    public function viewDashboard(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'day');

        $start = $end = null;

        switch ($filter) {
            case 'day':
                $start = Carbon::today();
                $end = $start->copy()->endOfDay();
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'all':
            default:
                break;
        }

        $applyDateFilter = function ($query) use ($start, $end, $user) {
            $query->where('sender', 'user')->where('userID', $user->userID);
            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
            return $query;
        };

        $totalMessages = $applyDateFilter(Message::query())->count();
        $answeredMessages = $applyDateFilter(Message::whereNotNull('responded_at'))->count();
        $unAnsweredMessages = $applyDateFilter(Message::whereNull('responded_at'))->count();

        // Total users with role 'user'
        $totalUsers = User::where('role', 'user')->count();

        $admissionMessages = $applyDateFilter(Message::where('categoryID', 1))->count();
        $scholarshipMessages = $applyDateFilter(Message::where('categoryID', 2))->count();
        $placementMessages = $applyDateFilter(Message::where('categoryID', 3))->count();
        $generalMessages = $applyDateFilter(Message::where('categoryID', 4))->count();

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
            for ($i = 0; $i < 24; $i++) {
                $hourStart = $start->copy()->addHours($i);
                $hourEnd = $hourStart->copy()->endOfHour();

                $labels->push($hourStart->format('H:00'));

                $count = Message::where('sender', 'user')
                    ->where('userID', $user->userID)
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->count();

                $counts->push($count);
            }
        } elseif ($filter === 'week') {
            for ($i = 0; $i < 7; $i++) {
                $day = $start->copy()->addDays($i);
                $labels->push($day->format('M d'));
        
                $startOfDay = $day->copy()->startOfDay();
                $endOfDay = $day->copy()->endOfDay();
        
                $count = Message::where('sender', 'user')
                    ->where('userID', $user->userID)
                    ->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->count();
        
                $counts->push($count);
            
        }
        } elseif ($filter === 'month') {
            $monthsInRange = $start->diffInMonths($end) + 1;

            for ($i = 0; $i < $monthsInRange; $i++) {
                $monthStart = $start->copy()->addMonths($i)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();

                $labels->push($monthStart->format('M Y'));

                $count = Message::where('sender', 'user')
                    ->where('userID', $user->userID)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();

                $counts->push($count);
            }
        } elseif ($filter === 'all') {
            $years = Message::where('sender', 'user')
                ->where('userID', $user->userID)
                ->select(DB::raw('YEAR(created_at) as year'))
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('year');

            foreach ($years as $year) {
                $labels->push($year);

                $count = Message::where('sender', 'user')
                    ->where('userID', $user->userID)
                    ->whereYear('created_at', $year)
                    ->count();

                $counts->push($count);
            }
        }

        // Message breakdown by category
        $categoryQuery = Message::where('sender', 'user')
            ->where('userID', $user->userID)
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

        $recentLogs = Logs::with(['user', 'message'])
            ->where('userID', $user->userID)
            ->whereNotNull('messageID') // ensures there's a message ID
            ->latest('created_at')
            ->take(10)
            ->get()
            ->filter(fn($log) => $log->message !== null)
            ->take(5);

        return view('user.dashboard', [
            'user' => $user,
            'totalMessages' => $totalMessages,
            'answeredMessages' => $answeredMessages,
            'unAnsweredMessages' => $unAnsweredMessages,
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
            'totalUsers' => $totalUsers,  // If you want to pass totalUsers
        ]);
    }



}
