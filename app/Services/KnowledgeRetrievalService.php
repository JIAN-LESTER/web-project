<?php
namespace App\Services;

use App\Models\KnowledgeBase;

class KnowledgeRetrievalService
{
    protected $openAI;

    public function __construct(OpenAIService $openAI)
    {
        $this->openAI = $openAI;
    }

    public function retrieveRelevant($query, $topK = 5)
{
    $queryEmbedding = $this->openAI->generateEmbedding($query);
    $kbItems = KnowledgeBase::all();

    $similarities = [];

    foreach ($kbItems as $kb) {
        $kbEmbedding = json_decode($kb->embedding, true);
        $similarity = $this->cosineSimilarity($queryEmbedding, $kbEmbedding);
        $similarities[] = ['kb' => $kb, 'score' => $similarity];
    }

    // Sort by highest similarity
    usort($similarities, fn($a, $b) => $b['score'] <=> $a['score']);

    // Get top K relevant documents
    return collect($similarities)->take($topK)->pluck('kb')->toArray();
}

    private function cosineSimilarity($vecA, $vecB)
    {
        $dotProduct = array_sum(array_map(fn($a, $b) => $a * $b, $vecA, $vecB));
        $magnitudeA = sqrt(array_sum(array_map(fn($a) => $a * $a, $vecA)));
        $magnitudeB = sqrt(array_sum(array_map(fn($b) => $b * $b, $vecB)));
    
        // Avoid division by zero by returning 0 if either magnitude is 0
        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0; // Return 0 as similarity if either magnitude is 0
        }
    
        return $dotProduct / ($magnitudeA * $magnitudeB);
    }
}




?>