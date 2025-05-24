<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Conversation;
use App\Models\Faq;
use App\Models\Logs;
use App\Models\Message;
use App\Services\CohereService;
use App\Services\KnowledgeRetrievalService;
use Auth;
use DB;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    
    protected $kbRetrieval;
   
    protected $llm;

    public function __construct(CohereService $llm, KnowledgeRetrievalService $kbRetrieval)
    {
        $this->kbRetrieval = $kbRetrieval;
   
        $this->llm = $llm;
    }

    public function index(Request $request)
    {
        // Get search query and category filter from request
        $query = $request->input('query');
        $categoryId = $request->input('category');

        // Get categories to populate dropdown
        $categories = Categories::all();

        // Start query builder for FAQs, eager load category relation
        $faqsQuery = Faq::with('category');

        // Apply search filter (on question or category name)
        if (!empty($query)) {
            $faqsQuery->where(function($q) use ($query) {
                $q->where('question', 'LIKE', "%{$query}%")
                  ->orWhereHas('category', function($q2) use ($query) {
                      $q2->where('category_name', 'LIKE', "%{$query}%");
                  });
            });
        }

        // Apply category filter if selected
        if (!empty($categoryId)) {
            $faqsQuery->where('categoryID', $categoryId);
        }

        // Order by newest first (optional)
        $faqsQuery->orderBy('created_at', 'desc');

        // Paginate (10 per page here)
        $faqs = $faqsQuery->paginate(10)->withQueryString();

        // Return view with FAQs and categories
        return view('admin.faqs', compact('faqs', 'categories'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all();
        return view('admin.faqs_crud.add_faqs', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'nullable|string',
            'category' => 'required|exists:categories,categoryID',
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'categoryID' => $request->category,
        ]);

        return redirect()->route('faqs');
    }

    public function addFrequentMessagesToFaq()
    {
        $threshold = 15;
    
        $frequentMessages = DB::table('messages')
            ->select('content', 'categoryID', DB::raw('COUNT(*) as total'))
            ->where('sender', 'user')  // <-- only count user messages
            ->groupBy('content', 'categoryID')
            ->having('total', '>=', $threshold)
            ->get();
    
        $added = 0;
    
        foreach ($frequentMessages as $message) {
            $alreadyExists = Faq::whereRaw('LOWER(question) = ?', [strtolower($message->content)])
                                ->where('categoryID', $message->categoryID)
                                ->exists();
    
            if (! $alreadyExists) {
                Faq::create([
                    'question' => $message->content,
                    'answer' => null,
                    'categoryID' => $message->categoryID,
                ]);
    
                $added++;
            }
        }
    
        return response()->json([
            'message' => "Added $added new FAQ(s).",
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'nullable|string',
            'category' => 'required|exists:categories,categoryID',
        ]);
    
        $faq = Faq::findOrFail($id);  // Find the FAQ by id or fail
    
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'categoryID' => $request->category,
        ]);
    
        return redirect()->route('faqs');
    }
    

    public function edit($id)
{
    $faq = Faq::findOrFail($id);

    return response()->json([
        'question' => $faq->question,
        'answer' => $faq->answer,
        'category_id' => $faq->categoryID,  // Match JS key `category_id`
    ]);
}
    /**
     * Remove the specified resource from storage.
     */public function destroy(String $id)
{
    $faq = Faq::findOrFail($id);  // Find FAQ by id or fail
    $faq->delete();

    return redirect()->route('faqs');
}


public function handleFaqQuestion(Request $request)
{
    $request->validate([
        'faq_id' => 'required|integer|exists:faqs,faqID',
    ]);

    $user = Auth::user();
    $faqId = $request->input('faq_id');

    // Fetch FAQ with category
    $faq = Faq::with('category')->findOrFail($faqId);

    // Determine the user query (FAQ question)
    $userQuery = $faq->question;

    // Find active conversation for user, end if idle > 5 mins
    $conversation = Conversation::where('userID', $user->userID)
        ->where('conversation_status', 'active')
        ->latest()
        ->first();

    if ($conversation && $conversation->created_at->diffInMinutes(now()) >= 5) {
        $conversation->update(['conversation_status' => 'ended']);
        $conversation = null;
    }

    if (!$conversation) {
        \Log::info("FAQ Question for title: '" . $faq->question . "'");
        $title = $this->generateTitle($faq->question);
    
        // Remove double quotes from start and end if any
        $title = trim($title, '"');
    
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
        $newTitle = $this->generateTitle($userQuery);
        $newTitle = trim($newTitle, '"');  // Remove quotes here too
        $conversation->update(['conversation_title' => $newTitle]);
    }

        $category = $this->detectCategory($userQuery);
        if (!$category || !Categories::find($category)) {
            $category = 4; // Default to 'General'
        }

    // Save user's FAQ question as user message
    $userMessage = Message::create([
        'userID' => $user->userID,
        'conversationID' => $conversation->conversationID,
        'categoryID' => $category,
        'content' => $userQuery,
        'sender' => 'user',
        'message_status' => 'sent',
        'message_type' => 'text',
        'sent_at' => now(),
    ]);

    Logs::create([
        'userID' => Auth::id(),
        'messageID' => $userMessage->messageID,
        'action_type' => 'Sent a message',
    ]);

    // Use stored answer if exists, else generate AI response
    if (!empty($faq->answer)) {
        $responseText = $faq->answer;
        $kbID = null;
        $noAnswer = false;
    } else {
        $kbEntries = $this->kbRetrieval->retrieveRelevant($userQuery, 5);

        if (!empty($kbEntries)) {
            $context = implode("\n\n", array_map(fn($kb) => $kb['content'], $kbEntries));

            $formattedPrompt = <<<PROMPT
You are a helpful assistant.

- For factual or numeric answers (like calculations), respond plainly without any HTML.
- For detailed or list answers, use simple HTML tags like <strong>, <ul>, <li>, <br>. Keep in mind that make it as short as possible. 


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
            \Log::warning("AI returned no answer or error for FAQ ID {$faqId}, userID {$user->userID}");
            $responseText = "I'm sorry, I couldn't find a relevant answer to your question.";
            $noAnswer = true;
        } else {
            $noAnswer = false;
        }
    }

    // Save the bot's reply message
    $botMessage = Message::create([
        'userID' => $user->userID,
        'kbID' => $kbID,
        'conversationID' => $conversation->conversationID,
        'categoryID' => $category,
        'content' => $responseText,
        'sender' => 'bot',
        'message_status' => $noAnswer ? 'no_answer' : 'responded',
        'message_type' => 'text',
        'sent_at' => now(),
        'response_time' => 0,
    ]);

   

    // Update responded_at timestamp on user message
    $userMessage->update(['responded_at' => $botMessage->sent_at]);

    return response()->json([
        'user_message' => $userMessage,
        'bot_message' => $botMessage,
        'conversationID' => $conversation->conversationID,
    ]);
}

/**
 * Detect category ID (1-4) based on user query using LLM or keyword fallback.
 */
public function detectCategory(string $userQuery): int
{
    $llmResponse = trim($this->llm->generateCompletion($this->getCategoryPrompt($userQuery)));

    \Log::info("LLM Category Raw (trimmed): '" . $llmResponse . "'");

    if (preg_match('/\b[1-4]\b/', $llmResponse, $matches)) {
        $categoryId = (int) $matches[0];
        if (Categories::find($categoryId)) {
            return $categoryId;
        }
    }

    // Manual keyword fallback
    $userQueryLower = strtolower($userQuery);

    if (str_contains($userQueryLower, 'enroll') || str_contains($userQueryLower, 'admission')) {
        return 1; // Admissions
    }
    if (str_contains($userQueryLower, 'scholarship')) {
        return 2; // Scholarships
    }
    if (str_contains($userQueryLower, 'job') || str_contains($userQueryLower, 'placement')) {
        return 3; // Placements
    }

    return 4; // General fallback
}

/**
 * Prompt template for category classification
 */
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

/**
 * Generate conversation title from user query using LLM
 */
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
}

