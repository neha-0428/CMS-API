<?php

namespace App\Jobs\Article;

use App\Models\Article;
use App\Services\GeminiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreArticleJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels, Dispatchable;

    /**
     * Create a new job instance.
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(GeminiService $gemini): void
    {
        $article = Article::create([
            'title' => $this->data['title'],
            'summary' => $gemini->generateSummary($this->data['content']),
            'content' => $this->data['content'],
            'status' => $this->data['status'],
            'published_date' => $this->data['published_date'],
            'author_id' => $this->data['author_id']
        ]);

        $gemini->generateSlug($article, $this->data['title'], $this->data['content']);

        if (isset($this->data['category_id']) && is_array($this->data['category_id'])) {
            $article->categories()->attach($this->data['category_id']);
        }
    }
}
