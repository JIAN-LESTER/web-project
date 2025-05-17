<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function totalMessage(){
        $totalMessages = Message::count();

        return view('admin.dashboard', compact('totalMessages'));
    }

    public function mostCategory(){
        $mostCategory = Message::select('categoryID')
        ->groupBy('categoryID')
        ->orderBy('COUNT(*) DESC')
        ->limit(1)
        ->pluck('categoryID')
        ->first();
    }

    public function answeredMessages(){
        $answeredMessages = Message::whereNotNull('responded_at')->count();
        return view('admin.dashboard', compact('answeredMessages'));
    }

    public function unAnsweredMessages(){
        $unAnsweredMessages = Message::whereNull('responded_at')->count();
        return view('admin.dashboard', compact('unAnsweredMessages'));
    }
}
