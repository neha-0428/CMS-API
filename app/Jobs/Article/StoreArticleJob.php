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
        $baseSlug = $gemini->generateSlug($this->data['title'], $this->data['content']);
        $slug = $baseSlug;
        $count = 1;

        while (true) {
            try {
                $article = Article::create([
                    'title' => $this->data['title'],
                    'slug' => $slug,
                    'summary' => $gemini->generateSummary($this->data['content']),
                    'content' => $this->data['content'],
                    'status' => $this->data['status'],
                    'published_date' => $this->data['published_date'],
                    'author_id' => $this->data['author_id']
                ]);

                if (!empty($this->data['category_id']) && is_array($this->data['category_id'])) {
                    $article->categories()->sync($this->data['category_id']);
                }

                break;
            } catch (\Illuminate\Database\QueryException $e) {
                // duplicate slug
                if ($e->getCode() == 23000) {
                    $slug = $baseSlug . '-' . $count++;
                    
                } else {
                    throw $e;
                }
            }
        }
    }
}
