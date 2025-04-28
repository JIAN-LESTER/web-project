<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\KnowledgeBase;
use App\Models\Message;
use App\Services\OpenAIService;  // Import the OpenAIService
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $openAI;  // Declare OpenAIService

    // Inject the OpenAIService into the controller
    public function __construct(OpenAIService $openAI)
    {
        $this->openAI = $openAI;  // Initialize the OpenAIService
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
    $kbEntry = KnowledgeBase::where('question', 'LIKE', '%' . $userQuery . '%')->first();

    if ($kbEntry) {
        $kbID = $kbEntry->kbID;
        $responseText = $kbEntry->answer;
    } else {
        // If no answer is found in KB, use OpenAI API to generate a response
        $responseText = $this->openAI->generateCompletion($userQuery);  // Using OpenAI to generate a response
        $kbID = null; // No KB entry was used
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
