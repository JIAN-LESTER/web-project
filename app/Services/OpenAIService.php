<?php

namespace App\Services;

use GuzzleHttp\Client;
use Http;

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
        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/embeddings', [
            'input' => $text,
            'model' => 'text-embedding-ada-002',
        ]);
    
        if (!$response->successful()) {
            \Log::error('Embedding API failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [];
        }
    
        return $response['data'][0]['embedding'] ?? [];
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
                    'max_tokens' => 100,
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
