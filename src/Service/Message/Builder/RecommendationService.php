<?php

namespace App\Service\Message\Builder;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecommendationService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey,
    ) {
    }

    public function getRecommendation(string $description, float $temperature): string
    {
        $prompt = "Today is {$description}, temperature is {$temperature}°C. What would you recommend wearing or being prepared for?";

        try {
            $response = $this->httpClient->request(
                'POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                ],
                ]
            );

            $data = $response->toArray(false);

            return $data['choices'][0]['message']['content'] ?? 'No recommendation.';
        } catch (\Exception $e) {
            return 'No recommendation available.';
        }
    }
}
