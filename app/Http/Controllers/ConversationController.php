<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function loadMore(Request $request)
{
    $userID = auth()->user()->userID; // Or use $request->userID if passed
    $offset = $request->input('offset', 0);
    $limit = 10;

    $conversations = Conversation::where('userID', $userID)
        ->latest('updated_at')
        ->orderByDesc('conversationID')
        ->skip($offset)
        ->take($limit)
        ->get();

    return response()->json([
        'conversations' => $conversations,
    ]);
}
}
