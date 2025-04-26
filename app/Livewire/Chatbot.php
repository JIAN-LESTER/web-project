<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\KnowledgeBase;
use Http;
use Livewire\Component;

class Chatbot extends Component
{
    public $messages = [];
    public $input;
    public $conversation;

    public function mount(){
        $userID = auth()->id();
        $this->conversation = Conversation::firstOrCreate(['userID' => $userID]);
        $this->loadMessages();
    }

    public function loadMessages(){
        $this->messages = $this->conversation->message()->latest()->paginate(20);
    }

    public function sendMessage(){
        if (!$this->input)
            return;

        $this->conversation->message()->create([
            'sender' => 'user',
            'content' => $this->input,
        ]);

        $botReply = KnowledgeBase::matchAnswer($this->input) ?? $this->askAI($this->input);

        $this->conversation->message()->create([
            'sender' => 'bot',
            'content' => $botReply,
        ]);

        $this->input = '';
        $this->loadMessages();

    }

    public function askAI($message)
    {
        // Using OpenAI API to ask the AI for a reply
        try {
            $response = Http::withToken(env('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'))->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $message],
                ],
            ]);
            return $response->json()['choices'][0]['message']['content'] ?? 'Sorry, I couldn’t respond right now.';
        } catch (\Exception $e) {
            return 'Oops! Something went wrong.';
        }
    }


    public function render()
    {

        return view('user.chatbot');
    }


}
