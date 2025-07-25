<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class GeminiService
{
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';


    public function generateSlug(string $title, string $content): string
    {
        $prompt = "Give only one short, lowercase, URL-friendly slug (kebab-case) for the article titled \"$title\" and article content \"$content\".";
        $response = $this->callGemini($prompt);
        $rawSlug = is_array($response) ? $response[0] : $response;

        return Str::slug($rawSlug);
    }


    public function generateSummary(string $content): ?string
    {
        $prompt = "Summarize the article content below in 2-3 short sentences. Only return the summary text — no explanation, no extra words.\n\n$content";

        $summary = $this->callGemini($prompt);

        return $summary;
    }

    protected function callGemini(string $prompt): ?string
    {
        $response = Http::post($this->baseUrl . '?key=' . config('services.gemini.api_key'), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->successful() && isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            $raw = $response['candidates'][0]['content']['parts'][0]['text'];
            $clean = trim($raw, " \n\r\t`\"'");
            return $clean;
        }

        Log::error('Gemini API failed', ['response' => $response->json()]);
        return null;
    }
}
