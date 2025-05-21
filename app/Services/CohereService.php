<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CohereService
{
    public function generateEmbedding(string $text, string $type = 'search_query'): array
    {
        $response = Http::withToken(env('COHERE_API_KEY'))
            ->post('https://api.cohere.ai/v1/embed', [
                'texts' => [$text],
                'model' => 'embed-english-v3.0',
                'input_type' => $type,
            ]);

        return $response->json('embeddings.0');
    }

    public function generateCompletion(string $prompt): string
    {
        $response = Http::withToken(env('COHERE_API_KEY'))
            ->post('https://api.cohere.ai/v1/generate', [
                'model' => 'command-r-plus',
                'prompt' => $prompt,
                'max_tokens' => 300,
                'temperature' => 0.3,
                'stop_sequences' => ["--END--"]
            ]);

        return $response->json('generations.0.text');
    }
}
