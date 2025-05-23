<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Logs;
use App\Models\Message;
use App\Services\KnowledgeRetrievalService;

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
        $startTime = microtime(true); // Start timing
    
        // Check for active conversation
        $conversation = Conversation::where('userID', $user->userID)
            ->where('conversation_status', 'active')
            ->latest()
            ->first();
    
        // End previous conversation if idle for more than 5 minutes
        if ($conversation && $conversation->created_at->diffInMinutes(now()) >= 5) {
            $conversation->update(['conversation_status' => 'ended', 'created_at' => now()]);
            $conversation = null;
        }
    
        // Create new conversation if none exists or update title if needed
        if (!$conversation) {
            $title = $this->generateTitle($userQuery);
            $title = trim($title, '"');  // Remove leading and trailing double quotes
        
            $conversation = Conversation::create([
                'userID' => $user->userID,
                'conversation_status' => 'active',
                'conversation_title' => $title,
                'created_at' => now(),
            ]);
        } elseif (
            $conversation->conversation_title === null ||
            trim($conversation->conversation_title, '"') === 'New Conversation'
        ) {
            $title = $this->generateTitle($userQuery);
            $title = trim($title, '"');  // Remove quotes here too
            $conversation->update([
                'conversation_title' => $title,
            ]);
        }
    
        // Detect the category
        $category = $this->detectCategory($userQuery);
        if (!$category || !\App\Models\Categories::find($category)) {
            $category = 4; // Default to 'General'
        }
    
        // Save user message (no response_time yet)
        $message = Message::create([
            'userID' => $user->userID,
            'conversationID' => $conversation->conversationID,
            'categoryID' => (int) ($category ?? 4),
            'content' => $userQuery,
            'sender' => 'user',
            'message_status' => 'sent',
            'message_type' => 'text',
            'sent_at' => now(),
        ]);

        Logs::create([
            'userID' => Auth::id(),
            'messageID' => $message->messageID,
            'action_type' => 'Sent a message',
        ]);
    
        $kbEntries = $this->kbRetrieval->retrieveRelevant($userQuery, 5);

        if (!empty($kbEntries)) {
            $context = implode("\n\n", array_map(fn($kb) => $kb['content'], $kbEntries));

            $formattedPrompt = <<<PROMPT
            You are a helpful assistant.
            
            - For factual or numeric answers (like calculations), respond plainly without any HTML.
            - For detailed or list answers, use simple HTML tags like <strong>, <ul>, <li>, <br>.
            
            Context:
            $context
            
            Question:
            $userQuery
            
            Answer:
            PROMPT;
            
            $responseText = $this->llm->generateCompletion($formattedPrompt);
            $kbID = $kbEntries[0]['kbID'] ?? null;

        } else {
            $responseText = $this->llm->generateCompletion($userQuery);
            $kbID = null;
        }
    
        if (empty($responseText) || $responseText === '[NO_ANSWER]' || str_contains($responseText, 'error')) {
            $responseText = "I'm sorry, I couldn't find a relevant answer to your question.";
            $noAnswer = true;
        } else {
            $noAnswer = false;
        }
    
        $endTime = microtime(true); // End timing
        $responseTime = round($endTime - $startTime, 4);
    
        // Save bot message with response time
        $botMessage = Message::create([
            'userID' => $user->userID,
            'kbID' => $kbID,
            'conversationID' => $conversation->conversationID,
            'content' => $responseText,
            'categoryID' => $category,
            'sender' => 'bot',
            'message_status' => $noAnswer ? 'no_answer' : 'responded',
            'message_type' => 'text',
            'sent_at' => now(),
            'response_time' => $responseTime, // Saved here
        ]);

    
        // Update responded_at on the latest user message
        if (!$noAnswer) {
            $latestUserMessage = Message::where('conversationID', $conversation->conversationID)
                ->where('sender', 'user')
                ->latest('sent_at')
                ->first();
    
            if ($latestUserMessage) {
                $latestUserMessage->update(['responded_at' => $botMessage->sent_at]);
            }
        }
        
    
        return response()->json([
            'message' => $responseText,
            'response_time' => $responseTime, // Also return to frontend
        ]);
    }
    

    public function detectCategory($userQuery)
{
    $llmResponse = trim($this->llm->generateCompletion($this->getCategoryPrompt($userQuery)));

    // Log the exact raw response including spaces or newlines
    \Log::info("LLM Category Raw (trimmed): '" . $llmResponse . "'");

    // Regex to extract numbers (ignores extra text like "Category: 1")
    if (preg_match('/\b[1-4]\b/', $llmResponse, $matches)) {
        // Ensure the category exists
        $categoryId = (int) $matches[0];
        if (\App\Models\Categories::find($categoryId)) {
            return $categoryId; // Return valid category ID
        }
    }

    // Fallback to manual keyword-based classification
    $userQuery = strtolower($userQuery);

    if (str_contains($userQuery, 'enroll') || str_contains($userQuery, 'admission'))
        return 1;
    if (str_contains($userQuery, 'scholarship'))
        return 2;
    if (str_contains($userQuery, 'job') || str_contains($userQuery, 'placement'))
        return 3;

    return 4; // Default to 'General' if nothing matches
}



    private function getCategoryPrompt(string $query): string
    {
        return <<<EOT
Classify the message below into one of these categories (return the number only):

1 - Admissions  
2 - Scholarships  
3 - Placements  
4 - General  

Message: "$query"

Just return the number only (1-4).
EOT;
    }




    public function showConversation($conversationID)
    {
        $user = Auth::user();

        $conversation = Conversation::where('conversationID', $conversationID)
            ->where('userID', $user->userID)
            ->firstOrFail();

        $messages = Message::where('conversationID', $conversationID)
            ->orderBy('sent_at', 'asc')
            ->get();

        return view('user.chatbot', [
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function fetchMessages($conversationID)
    {
        $user = Auth::user();

        $conversation = Conversation::where('conversationID', $conversationID)
            ->where('userID', $user->userID)
            ->firstOrFail();

        $messages = Message::where('conversationID', $conversationID)
            ->orderBy('sent_at', 'asc')
            ->get();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    private function generateTitle(string $userQuery): string
    {
        if (empty(trim($userQuery))) {
            return 'New Conversation';
        }
    
        $prompt = <<<EOT
    Summarize the following message into a short, clear conversation title (3-7 words):
    
    Message: "$userQuery"
    
    Title:
    EOT;
    
        $title = $this->llm->generateCompletion($prompt);
    
        $title = trim($title);
    
        if (empty($title)) {
            $title = 'New Conversation';
        }
    
        if (strlen($title) > 50) {
            $title = substr($title, 0, 50) . '...';
        }
    
        return $title;
    }
    public function newChat(Request $request)
    {
        $user = Auth::user();

        // Check if there's already an active conversation and end it
        $activeConversation = Conversation::where('userID', $user->userID)
            ->where('conversation_status', 'active')
            ->latest()
            ->first();

        if ($activeConversation) {
            // End the active conversation
            $activeConversation->update([
                'conversation_status' => 'ended',
                'created_at' => now(),
            ]);
        }

        // Create a new conversation
        $userQuery = $request->input('message') ?? '';
        if (empty(trim($userQuery))) {
            $userQuery = 'New conversation';
        }
    

        $conversation = Conversation::create([
            'userID' => $user->userID,
            'conversation_status' => 'active',
            'conversation_title' => $this->generateTitle($userQuery),
            'created_at' => now(),
        ]);

        // Redirect to the new conversation's view
        return redirect()->route('chatbot', ['conversationID' => $conversation->conversationID]);
    }
}
