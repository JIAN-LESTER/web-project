<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\KnowledgeBase;
use App\Models\Message;
use App\Services\KnowledgeRetrievalService;
use App\Services\OpenAIService;  // Import the OpenAIService
use Illuminate\Http\Request;
use App\Services\CohereService; 
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $llm;
    protected $kbRetrieval;

    public function __construct(KnowledgeRetrievalService $kbRetrieval, CohereService $llm)
    {
        $this->kbRetrieval = $kbRetrieval;
        $this->llm = $llm;
    }

    public function handleChat(Request $request)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $user = Auth::user();
    $userQuery = $request->input('message');

    // Check for existing active conversation
    $conversation = Conversation::where('userID', $user->userID)
        ->where('conversation_status', 'active')  // Use 'active' instead of 'pending'
        ->latest()
        ->first();

    if (!$conversation) {
        $conversation = Conversation::create([
            'userID' => $user->userID,
            'conversation_status' => 'active',
            'sent_at' => now(),
        ]);
    }

    // Detect category of the message
    $category = $this->detectCategory($userQuery);

    // Save user message with a fallback for categoryID
    Message::create([
        'userID' => $user->userID,
        'conversationID' => $conversation->conversationID,
        'categoryID' => (int) ($category ?? 4),  // Always ensure categoryID is an integer
        'content' => $userQuery,
        'sender' => 'user',
        'message_status' => 'sent',
        'message_type' => 'text',
        'sent_at' => now(),
    ]);

    // Check Knowledge Base for an answer
    $kbEntry = $this->kbRetrieval->retrieveRelevant($userQuery);

if ($kbEntry && $kbEntry->content) {
            $kbID = $kbEntry->kbID;
    $context = $kbEntry->content;
    $responseText = $this->llm->generateCompletion("Context:\n$context\n\nQuestion:\n$userQuery");
} else {
    $responseText = $this->llm->generateCompletion($userQuery);
            $kbID = null;
}


    // Save bot response
    Message::create([
        'userID' => $user->userID,
        'kbID' => $kbID,
        'conversationID' => $conversation->conversationID,
        'content' => $responseText,
        'categoryID' => (int) ($category ?? 4),  // Ensure categoryID is always an integer
        'sender' => 'bot',
        'message_status' => 'responded',
        'message_type' => 'text',
        'sent_at' => now(),
        'responded_at' => now(),
    ]);

    return response()->json([
        'message' => $responseText,  // Return the bot response
    ]);
}


    public function detectCategory($userQuery)
    {
        $message = strtolower($userQuery);

        if (str_contains($message, 'admission') || str_contains($message, 'enrollment') || str_contains($message, 'requirement')) {
            return 1;
        } elseif (str_contains($message, 'scholarship') || str_contains($message, 'grant') || str_contains($message, 'financial')) {
            return 2;
        } elseif (str_contains($message, 'placement') || str_contains($message, 'job') || str_contains($message, 'internship')) {
            return 3;
        } else {
            return 4; // fallback
        }
    }
}
