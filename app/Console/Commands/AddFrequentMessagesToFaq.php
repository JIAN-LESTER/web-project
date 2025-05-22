<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Faq;

class AddFrequentMessagesToFaq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faq:add-frequent-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add messages that occur 15+ times to the FAQ table automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = 15;
    
        $this->info('Checking for frequent messages...');
    
        $frequentMessages = DB::table('messages')
            ->select('content', 'categoryID', DB::raw('COUNT(*) as total'))
            ->where('sender', 'user')  // Only user messages
            ->groupBy('content', 'categoryID')
            ->having('total', '>=', $threshold)
            ->get();
    
        $addedCount = 0;
    
        foreach ($frequentMessages as $message) {
            $alreadyExists = Faq::whereRaw('LOWER(question) = ?', [strtolower($message->content)])
                                ->where('categoryID', $message->categoryID)
                                ->exists();
    
            if (!$alreadyExists) {
                Faq::create([
                    'question' => $message->content,
                    'answer' => null,
                    'categoryID' => $message->categoryID,
                ]);
                $addedCount++;
                $this->info("Added FAQ: '{$message->content}'");
            }
        }
    
        $this->info("Finished. Total new FAQs added: $addedCount");
    }
}
