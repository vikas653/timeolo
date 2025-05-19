<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->client = new Client([
            'connect_timeout' => 5,
            'timeout' => 15,
        ]);

        $this->apiKey = env('GEMINI_API_KEY');
        $this->model = env('GEMINI_MODEL', 'gemini-1.5-flash-latest');

        if (!$this->apiKey) {
            Log::error('Gemini API key is missing in .env');
            throw new \Exception('Gemini API key is not configured.');
        }
    }

    public function summarizeText(string $text): string
    {
        if (empty(trim($text))) {
            return 'No activity details available to summarize.';
        }

        $cacheKey = 'summary_' . md5($text);

        return Cache::remember($cacheKey, 3600, function () use ($text) {
            try {
                $prompt = "Summarize this timesheet activity in 1-2 sentences (max 50 words), including any code if present: $text";

                $response = $this->client->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent", [
                    'headers' => ['Content-Type' => 'application/json'],
                    'query' => ['key' => $this->apiKey],
                    'json' => [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ]
                    ]
                ]);

                $data = json_decode($response->getBody(), true);
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Summary not available.';

            } catch (RequestException $e) {
                Log::error('Gemini API error', [
                    'message' => $e->getMessage(),
                    'text' => $text,
                ]);
                return 'Error generating summary: ' . $e->getMessage();
            }
        });
    }
}
