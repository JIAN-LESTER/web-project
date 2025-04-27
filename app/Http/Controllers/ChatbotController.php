<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\KnowledgeBase;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function handleChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();
        $userQuery = $request->input('message');

        $conversation = Conversation::where('userID', $user->userID)
            ->where('conversation_status', 'pending ')
            ->latest()
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'userID' => $user->userID,
                'conversation_status' => 'pending',
                'sent_at' => now(),
            ]);
        }

        // Save user message
        Message::create([
            'userID' => $user->userID,
            'conversationID' => $conversation->conversationID,
            'content' => $userQuery,
            'sender' => 'user',
            'message_status' => 'sent',
            'message_type' => 'text',
            'sent_at' => now(),
        ]);

        // Search KB
        $kbEntry = KnowledgeBase::where('question', 'LIKE', '%' . $userQuery . '%')->first();

        if ($kbEntry) {
            $kbID = $kbEntry->kbID;
            $responseText = $kbEntry->answer;
        } else {
            $kbID = null;
            $responseText = "I'm sorry, I don't have an answer for that.";
        }

        // Save bot response
        Message::create([
            'userID' => $user->userID,
            'kbID' => $kbID,
            'conversationID' => $conversation->conversationID,
            'content' => $responseText,
            'sender' => 'bot',
            'message_status' => 'responded',
            'message_type' => 'text',
            'sent_at' => now(),
            'responded_at' => now(),
        ]);

        return response()->json([
            'message' => $responseText, // <-- corrected key
        ]);
    }
}
