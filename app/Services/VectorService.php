<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VectorService
{
    public static function upsert(string $id, string $text, array $embedding)
    {
        return Http::withHeaders([
            'Api-Key' => env('PINECONE_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post(env('PINECONE_UPSERT_URL'), [
            'vectors' => [[
                'id' => $id,
                'values' => $embedding,
                'metadata' => ['content' => $text]
            ]]
        ]);
    }

    public static function query(array $embedding, int $topK = 5): array
    {
        $response = Http::withHeaders([
            'Api-Key' => env('PINECONE_API_KEY'),
        ])->post(env('PINECONE_QUERY_URL'), [
            'vector' => $embedding,
            'topK' => $topK,
            'includeMetadata' => true,
        ]);

        return collect($response['matches'])->pluck('metadata.content')->toArray();
    }
}
