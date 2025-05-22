<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Logs;
use App\Models\Message;
use App\Models\User;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsController extends Controller
{
    private function getDateRange(Request $request)
    {
        $filter = $request->query('filter', 'day');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');
    
        if ($filter === 'all') {
            $first = Message::where('sender', 'user')->orderBy('created_at')->first();
            $last = Message::where('sender', 'user')->orderByDesc('created_at')->first();
    
            $start = $first ? Carbon::parse($first->created_at)->startOfDay() : null;
            $end   = $last  ? Carbon::parse($last->created_at)->endOfDay()   : null;
    
            return [$start, $end];
        }
        elseif ($filter === 'custom') {
            if ($startDate && $endDate) {
                $start = Carbon::parse("{$startDate} " . ($startTime ?? '00:00:00'));
                $end   = Carbon::parse("{$endDate} " . ($endTime ?? '23:59:59'));
            } else {
                // Handle missing start/end in custom
                abort(400, 'Custom filter requires start_date and end_date.');
            }
        } 
    
        if ($startDate && $endDate) {
            $start = Carbon::parse("{$startDate} " . ($startTime ?? '00:00:00'));
            $end   = Carbon::parse("{$endDate} " . ($endTime ?? '23:59:59'));
        } else {
            switch ($filter) {
                case 'week':
                    $start = Carbon::now()->startOfWeek();
                    $end   = Carbon::now()->endOfWeek();
                    break;
                case 'month':
                    $start = Carbon::now()->startOfMonth();
                    $end   = Carbon::now()->endOfMonth();
                    break;
                case 'day':
                default:
                    $start = Carbon::today();
                    $end   = Carbon::today()->endOfDay();
                    break;
            }
        }
    
        return [$start, $end];
    }
    private function getMessageStats($start, $end)
    {
        $totalMessages = Message::where('sender', 'user')->whereBetween('created_at', [$start, $end])->count();
        $answeredMessages = Message::where('sender', 'user')->whereNotNull('responded_at')->whereBetween('created_at', [$start, $end])->count();
        $unAnsweredMessages = Message::where('sender', 'user')->whereNull('responded_at')->whereBetween('created_at', [$start, $end])->count();

        $admissionMessages = Message::where('sender', 'user')->where('categoryID', 1)->whereBetween('created_at', [$start, $end])->count();
        $scholarshipMessages = Message::where('sender', 'user')->where('categoryID', 2)->whereBetween('created_at', [$start, $end])->count();
        $placementMessages = Message::where('sender', 'user')->where('categoryID', 3)->whereBetween('created_at', [$start, $end])->count();
        $generalMessages = Message::where('sender', 'user')->where('categoryID', 4)->whereBetween('created_at', [$start, $end])->count();
    

        $avgTime = Message::where('sender', 'bot')->avg('response_time');

        $mostCategoryData = Message::whereNotNull('categoryID')
            ->where('sender', 'user')
            ->whereBetween('created_at', [$start, $end])
            ->select('categoryID', DB::raw('COUNT(*) as total'))
            ->groupBy('categoryID')
            ->orderByDesc('total')
            ->first();

        $mostCategory = $mostCategoryData->categoryID ?? null;
        $mostCategoryCount = $mostCategoryData->total ?? 0;
        $mostCategoryName = Categories::find($mostCategory)?->category_name ?? 'Unknown';

        return compact(
            'totalMessages',
            'answeredMessages',
            'unAnsweredMessages',
            'admissionMessages',
            'scholarshipMessages',
            'placementMessages',
            'generalMessages',
            'mostCategoryName',
            'mostCategoryCount',
            'avgTime'
        );
    }

    private function getChartData($filter, $start, $end)
    {
        $labels = collect();
        $counts = collect();
    
        if ($filter === 'all' || (!$start && !$end)) {
            $firstMessage = Message::where('sender', 'user')->orderBy('created_at')->first();
    
            if ($firstMessage) {
                $start = Carbon::parse($firstMessage->created_at)->startOfMonth();
                $end = now()->endOfMonth();
                $monthsToShow = $start->diffInMonths($end) + 1;
    
                for ($i = 0; $i < $monthsToShow; $i++) {
                    $monthStart = $start->copy()->addMonths($i)->startOfMonth();
                    $monthEnd = $monthStart->copy()->endOfMonth();
                    $labels->push($monthStart->format('M Y'));
                    $counts->push(
                        Message::where('sender', 'user')->whereBetween('created_at', [$monthStart, $monthEnd])->count()
                    );
                }
            }
        } elseif ($filter === 'day') {
            for ($i = 0; $i < 24; $i++) {
                $hourStart = $start->copy()->setTime($i, 0, 0);
                $hourEnd = $hourStart->copy()->endOfHour();
    
                $labels->push($hourStart->format('H:00'));
                $counts->push(
                    Message::where('sender', 'user')->whereBetween('created_at', [$hourStart, $hourEnd])->count()
                );
            }
        } elseif ($filter === 'custom' && $start && $end) {
            for ($i = 0; $i <= $start->diffInDays($end); $i++) {
                $dayStart = $start->copy()->addDays($i)->startOfDay();
                $dayEnd = $dayStart->copy()->endOfDay();
    
                $labels->push($dayStart->format('M d'));
                $counts->push(
                    Message::where('sender', 'user')->whereBetween('created_at', [$dayStart, $dayEnd])->count()
                );
            }
        } elseif ($filter === 'week') {
            for ($i = 0; $i < 7; $i++) {
                $dayStart = $start->copy()->addDays($i)->startOfDay();
                $dayEnd = $dayStart->copy()->endOfDay();
    
                $labels->push($dayStart->format('M d'));
                $counts->push(
                    Message::where('sender', 'user')->whereBetween('created_at', [$dayStart, $dayEnd])->count()
                );
            }
        } elseif ($filter === 'month') {
            $monthsToShow = 6;
            $start = $start ?: Carbon::now()->subMonths($monthsToShow - 1)->startOfMonth();
    
            for ($i = 0; $i < $monthsToShow; $i++) {
                $monthStart = $start->copy()->addMonths($i)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();
    
                $labels->push($monthStart->format('M Y'));
                $counts->push(
                    Message::where('sender', 'user')->whereBetween('created_at', [$monthStart, $monthEnd])->count()
                );
            }
        }
    
        return ['labels' => $labels, 'counts' => $counts];
    }

private function getCategoryPieData($start, $end)
{
    $categoryData = Message::where('sender', 'user')
        ->whereBetween('created_at', [$start, $end])
        ->select('categoryID', DB::raw('COUNT(*) as total'))
        ->groupBy('categoryID')
        ->get();

    $categories = Categories::pluck('category_name', 'categoryID');

    $categoryLabels = collect();
    $categoryCounts = collect();

    foreach ($categoryData as $data) {
        $categoryLabels->push($categories->get($data->categoryID, 'Unknown'));
        $categoryCounts->push($data->total);
    }

    return [
        'categoryLabels' => $categoryLabels,
        'categoryCounts' => $categoryCounts
    ];
}
    private function getUserInsights()
    {
        $topYear = User::join('years', 'users.yearID', '=', 'years.yearID')
            ->select('years.year_level')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('years.year_level')
            ->orderByDesc('count')
            ->get();

        $topCourse = User::join('courses', 'users.courseID', '=', 'courses.courseID')
            ->select('courses.course_name')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('courses.course_name')
            ->orderByDesc('count')
            ->get();


        $totalUsers = User::count();
      

        $totalMessages = Message::where('sender', 'user')->count();

        $messagesPerUser = Message::where('sender', 'user')
            ->select('userID')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('userID')
            ->orderByDesc('count')
            ->get();

        return [
            'topYearLevel' => $topYear,
            'topCourse' => $topCourse,
            'totalUsers' => $totalUsers,
            'totalMessages' => $totalMessages,
            'averageMessagesPerUser' => $messagesPerUser,
            'messagesPerUser' => $messagesPerUser,
        ];
    }

    private function getUserMessageInsights()
{
    // Top year levels by number of inquiries (messages)
    $topYearMessage = Message::join('users', 'messages.userID', '=', 'users.userID')
        ->join('years', 'users.yearID', '=', 'years.yearID')
        ->where('messages.sender', 'user')
        ->select('years.year_level')
        ->selectRaw('COUNT(messages.messageID) as count') 
        ->groupBy('years.year_level')
        ->orderByDesc('count')
        ->get();

    // Top courses by number of inquiries (messages)
    $topCourseMessage = Message::join('users', 'messages.userID', '=', 'users.userID')
        ->join('courses', 'users.courseID', '=', 'courses.courseID')
        ->where('messages.sender', 'user')
        ->select('courses.course_name')
        ->selectRaw('COUNT(messages.messageID) as count')
        ->groupBy('courses.course_name')
        ->orderByDesc('count')
        ->get();

    // Total users count
    $totalUsers = User::count();

    // Total messages from users
    $totalMessages = Message::where('sender', 'user')->count();

    // Messages per user (number of inquiries per user)
    $messagesPerUser = Message::where('sender', 'user')
        ->select('userID')
        ->selectRaw('COUNT(*) as count')
        ->groupBy('userID')
        ->orderByDesc('count')
        ->get();

    // Calculate average messages per user
    $averageMessagesPerUser = $messagesPerUser->avg('count');

    return [
        'topYearLevelMessage' => $topYearMessage,
        'topCourseMessage' => $topCourseMessage,
        'totalUsers' => $totalUsers,
        'totalMessages' => $totalMessages,
        'averageMessagesPerUser' => $averageMessagesPerUser,
        'messagesPerUser' => $messagesPerUser,
    ];
}

private function getPeakInsights()
{
    // Peak hour without timezone conversion
    $rawPeakHour = DB::table('messages')
        ->selectRaw("HOUR(created_at) as hour, COUNT(*) as count")
        ->where('sender', 'user')
        ->groupBy('hour')
        ->pluck('count', 'hour');

    $peakHour = collect(range(0, 23))->map(function ($hour) use ($rawPeakHour) {
        return [
            'hour' => date('g A', mktime($hour)), // 12-hour format with AM/PM
            'count' => $rawPeakHour[$hour] ?? 0,
        ];
    });

    // Peak day without timezone conversion
    $peakDay = Message::where('sender', 'user')
        ->selectRaw("DATE(created_at) as day, COUNT(*) as count")
        ->groupBy('day')
        ->orderBy('count')
        ->get()
        ->map(function ($item) {
            $item->day = Carbon::parse($item->day)->format('M d');
            return $item;
        });

    return compact('peakHour', 'peakDay');
}

    public function viewReports(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'day');
        [$start, $end] = $this->getDateRange($request);



        $stats = $this->getMessageStats($start, $end);
        $chartData = $this->getChartData($filter, $start, $end);
        $pieData = $this->getCategoryPieData($start, $end);
        $userInsights = $this->getUserInsights();

        $peakInsights = $this->getPeakInsights();
        $recentLogs = Logs::with('user')->latest('created_at')->take(5)->get();
        $avgPerDay = $this->getAverageMessagesPerDay($start, $end);

        $userMessageInsights = $this->getUserMessageInsights();

        // Prepare chart labels and counts from insights
        $topYearLabels = $userMessageInsights['topYearLevelMessage']->pluck('year_level');
        $topYearCounts = $userMessageInsights['topYearLevelMessage']->pluck('count');
    
        $topCourseLabels = $userMessageInsights['topCourseMessage']->pluck('course_name');
        $topCourseCounts = $userMessageInsights['topCourseMessage']->pluck('count');

        return view('admin.reports_analytics', array_merge(
            compact('user', 'filter', 'recentLogs', 'topYearLabels', 'topYearCounts',
        'topCourseLabels', 'topCourseCounts',),
            $stats,
            $chartData,
            $pieData,
            $userInsights,
            $userMessageInsights,
            $peakInsights,
            compact('avgPerDay')

        ));
    }

    public function ajaxReportData(Request $request)
{
    try {
        $filter = $request->query('filter', 'day');
        $allowedFilters = ['day', 'week', 'month', 'year'];
        if (!in_array($filter, $allowedFilters)) {
            $filter = 'day';
        }

        [$start, $end] = $this->getDateRange($request);

        $stats = $this->getMessageStats($start, $end);
        $chartData = $this->getChartData($filter, $start, $end);
        $pieData = $this->getCategoryPieData($start, $end);
        $userInsights = $this->getUserInsights();
        $userMessageInsights = $this->getUserMessageInsights();
        $peakInsights = $this->getPeakInsights();
        $avgPerDay = $this->getAverageMessagesPerDay($start, $end);

        $recentLogs = Logs::with('user')
            ->latest('timestamp')
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'user' => $log->user?->name ?? 'System',
                    'timestamp' => $log->timestamp->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'stats' => $stats,
            'chart' => $chartData,
            'pie' => $pieData,
            'recentLogs' => $recentLogs,
            'userInsights' => $userInsights,
            'userMessageInsights' => $userMessageInsights,
            'peakInsights' => $peakInsights,
            'avgPerDay' => $avgPerDay,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch report data.',
            'message' => $e->getMessage(),
        ], 500);
    }
}


public function exportReports(Request $request)
{
    $filter = $request->query('filter', 'day');
    [$start, $end] = $this->getDateRange($request);
    $avgPerDay = $this->getAverageMessagesPerDay($start, $end);

    $stats = $this->getMessageStats($start, $end);
    $chartData = $this->getChartData($filter, $start, $end);
    $pieData = $this->getCategoryPieData($start, $end); // Add this line
    $userInsights = $this->getUserInsights();
    $userMessageInsights = $this->getUserMessageInsights();
    $peakInsights = $this->getPeakInsights();

    $categoryMessages = [];
foreach ($pieData['categoryLabels'] as $index => $category) {
    $categoryMessages[$category] = $pieData['categoryCounts'][$index];
}


    $pdf = Pdf::loadView('admin.pdfReport', array_merge(
        compact('filter',  'avgPerDay'),
        $stats,
        $chartData,
        $pieData,        // Include categoryLabels and categoryCounts
        $userInsights,
        $userMessageInsights,
        $peakInsights,
        compact('categoryMessages')
    ));

    return $pdf->download('analytics-report-' . now()->format('Y-m-d_H-i') . '.pdf');
}

public function exportCsv(Request $request)
{
    $filter = $request->query('filter', 'day');
    [$start, $end] = $this->getDateRange($request);

    $avgPerDay = $this->getAverageMessagesPerDay($start, $end);
    $stats = $this->getMessageStats($start, $end);
    $userInsights = $this->getUserInsights();
    $userMessageInsights = $this->getUserMessageInsights();
    $peakInsights = $this->getPeakInsights();

    $topYearLevelMessage = $userMessageInsights['topYearLevelMessage']->first();
    $topCourseMessage = $userMessageInsights['topCourseMessage']->first();

    $totalUsers = $userMessageInsights['totalUsers'] ?? 0;
    $messagesPerUser = $userMessageInsights['averageMessagesPerUser'] ?? 0;

    $topYearLevel = $userInsights['topYearLevel']->first();
    $topCourse = $userInsights['topCourse']->first();

    $response = new StreamedResponse(function () use (
        $stats, $topYearLevel, $topCourse, $topYearLevelMessage, $topCourseMessage,
        $totalUsers, $messagesPerUser, $peakInsights
    ) {
        $handle = fopen('php://output', 'w');

        // Section: Overview Stats
        fputcsv($handle, ['--- Overview Statistics ---']);
        fputcsv($handle, ['Metric', 'Value']);
        fputcsv($handle, ['Total Messages', $stats['totalMessages']]);
        fputcsv($handle, ['Answered Messages', $stats['answeredMessages']]);
        fputcsv($handle, ['Unanswered Messages', $stats['unAnsweredMessages']]);
        fputcsv($handle, ['Admission Messages', $stats['admissionMessages']]);
        fputcsv($handle, ['Scholarship Messages', $stats['scholarshipMessages']]);
        fputcsv($handle, ['Placement Messages', $stats['placementMessages']]);
        fputcsv($handle, ['General Messages', $stats['generalMessages']]);
        fputcsv($handle, ['Most Frequent Category', $stats['mostCategoryName'] ?? 'N/A']);
        fputcsv($handle, ['Most Frequent Category Count', $stats['mostCategoryCount'] ?? 0]);

        fputcsv($handle, ['']); // Blank line

        // Section: User Insights
        fputcsv($handle, ['--- User Insights ---']);
        fputcsv($handle, ['Top Year Level', $topYearLevel?->year_level ?? 'N/A']);
        fputcsv($handle, ['Top Year Level Count', $topYearLevel?->count ?? 0]);
        fputcsv($handle, ['Year with the most Inquiries', $topYearLevelMessage?->year_level ?? 'N/A']);
        fputcsv($handle, ['Inquiries Count (Year Level)', $topYearLevelMessage?->count ?? 0]);
        fputcsv($handle, ['Top Course', $topCourse?->course_name ?? 'N/A']);
        fputcsv($handle, ['Top Course Count', $topCourse?->count ?? 0]);
        fputcsv($handle, ['Course with the most Inquiries', $topCourseMessage?->course_name ?? 'N/A']);
        fputcsv($handle, ['Inquiries Count (Course)', $topCourseMessage?->count ?? 0]);
        fputcsv($handle, ['Total Registered Users', $totalUsers]);
        fputcsv($handle, ['Average Messages Per User', round($messagesPerUser, 2)]);

        fputcsv($handle, ['']); // Blank line

        // Section: Peak Time Insights
        fputcsv($handle, ['--- Peak Times ---']);
        fputcsv($handle, ['Peak Hour', 'Message Count']);
        foreach ($peakInsights['peakHour'] as $hourData) {
            $hour = date('H:00', is_numeric($hourData['hour']) ? $hourData['hour'] * 3600 : strtotime($hourData['hour']));
            fputcsv($handle, [$hour, $hourData['count']]);
        }

        fputcsv($handle, ['']); // Blank line

        fputcsv($handle, ['Peak Day', 'Message Count']);
        foreach ($peakInsights['peakDay'] as $dayData) {
            fputcsv($handle, [$dayData->day, $dayData->count]);
        }

        fclose($handle);
    });

    $filename = 'analytics-report-' . now()->format('Y-m-d_H-i') . '.csv';
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

    return $response;
}


    private function getAverageMessagesPerDay($start, $end)
    {
        if (!$start || !$end) {
            return 0;
        }
    
        $totalMessages = Message::whereBetween('created_at', [$start, $end])->count();
        $days = $start->diffInDays($end) + 1; // inclusive
    
        return $days > 0 ? round($totalMessages / $days, 2) : 0;
    }
}
