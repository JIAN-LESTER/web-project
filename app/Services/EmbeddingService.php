<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmbeddingService
{
    public static function embedDocument(string $text): array
    {
        $response = Http::withToken(env('COHERE_API_KEY'))
            ->post('https://api.cohere.ai/v1/embed', [
                'texts' => [$text],
                'model' => 'embed-english-v3.0',
                'input_type' => 'search_document',
            ]);

        return $response['embeddings'][0];
    }

    public static function embedQuery(string $text): array
    {
        $response = Http::withToken(env('COHERE_API_KEY'))
            ->post('https://api.cohere.ai/v1/embed', [
                'texts' => [$text],
                'model' => 'embed-english-v3.0',
                'input_type' => 'search_query',
            ]);

        return $response['embeddings'][0];
    }
}
