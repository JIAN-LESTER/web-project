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




    public function render()
    {

        return view('user.chatbot');
    }


}
