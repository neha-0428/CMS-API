<?php

namespace App\Jobs\Article;

use App\Models\Article;
use App\Services\GeminiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateArticleJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $article;
    protected $data;

    public function __construct(Article $article, array $data)
    {
        $this->article = $article;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    // public function handle(GeminiService $gemini): void
    // {

    //     $this->article->update([
    //         'title' => $this->data['title'],
    //         'slug' => $this->data['slug'],
    //         'summary' => $gemini->generateSummary($this->data['content']),
    //         'content' => $this->data['content'],
    //         'status' => $this->data['status'],
    //         'published_date' => $this->data['published_date'],
    //         'author_id' => $this->data['author_id']
    //     ]);

    //     if (isset($this->data['category_id']) && is_array($this->data['category_id'])) {
    //         $this->article->categories()->attach($this->data['category_id']);
    //     }
    // }

    public function handle(GeminiService $gemini): void
    {
        $baseSlug = $gemini->generateSlug($this->data['title'], $this->data['content']);
        $slug = $baseSlug;
        $count = 1;

        while (true) {
            try {
                $this->article->update([
                    'title' => $this->data['title'],
                    'slug' => $slug,
                    'summary' => $gemini->generateSummary($this->data['content']),
                    'content' => $this->data['content'],
                    'status' => $this->data['status'],
                    'published_date' => $this->data['published_date'],
                    'author_id' => $this->data['author_id']
                ]);

                if (!empty($this->data['category_id']) && is_array($this->data['category_id'])) {
                    $this->article->categories()->sync($this->data['category_id']);
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
