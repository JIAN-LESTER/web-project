<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LLMService
{
    public static function generateAnswer(string $query, array $documents): string
    {
        $context = implode("\n\n", $documents);
        $prompt = "Context:\n$context\n\nQuestion:\n$query";

        $response = Http::post('http://localhost:11434/api/generate', [
            'prompt' => $prompt,
            'model' => 'deepseek-coder', // or any model name
            'temperature' => 0.3,
        ]);

        return $response['response'] ?? 'No response.';
    }
}
