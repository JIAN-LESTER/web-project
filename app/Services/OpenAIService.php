<?php

namespace App\Services;

use GuzzleHttp\Client;
use Http;
use Symfony\Component\Process\Process;

class OpenAIService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl = 'https://api.openai.com/v1/';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPENAI_API_KEY');  // Store your API Key in .env
    }

    // Method to generate embeddings
    public function generateEmbedding($text)
    {
        $scriptPath = base_path('app/Python/scripts/generate_embeddings.py');
        $pythonPath = 'C:\Python312\python.exe'; 
    
        $process = new Process([$pythonPath, $scriptPath]);
        $process->setInput($text);
        $process->run();
    
        if (!$process->isSuccessful()) {
            \Log::error("Local embedding script failed", [
                'error' => $process->getErrorOutput(),
            ]);
            return [];
        }
    
        $output = $process->getOutput();
        return json_decode($output, true) ?? [];
    }
    // Method to generate a completion (chat)
    public function generateCompletion($prompt)
    {
        try {
            $response = $this->client->post($this->apiUrl . 'completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',  // You can choose the appropriate model
                    'prompt' => $prompt,
                    'max_tokens' => 1024,
                    'temperature' => 0.7,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['choices'][0]['text'] ?? 'Sorry, I could not generate a response.';
        } catch (\Exception $e) {
            return 'Error: Unable to generate a response.';
        }
    }


}
