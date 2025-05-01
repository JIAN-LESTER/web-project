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

    public function retrieveRelevant($query)
    {
        $queryEmbedding = $this->openAI->generateEmbedding($query);
        $kbItems = KnowledgeBase::all();

        $topKb = null;
        $topScore = -1;

        foreach ($kbItems as $kb) {
            $kbEmbedding = json_decode($kb->embedding, true);
            $similarity = $this->cosineSimilarity($queryEmbedding, $kbEmbedding);

            if ($similarity > $topScore) {
                $topScore = $similarity;
                $topKb = $kb;
            }
        }

        return $topKb;
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