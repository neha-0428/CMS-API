<?php

namespace App\Http\Controllers;

use App\Filters\ArticleFilter;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Jobs\Article\StoreArticleJob;
use App\Jobs\Article\UpdateArticleJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(ArticleFilter $filter)
    {
        $query = Article::with(['categories', 'author']);

        $query = $filter->apply($query);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user && $user->isAuthor()) {
            $query->where('author_id', Auth::id());
        }

        $articles = $query->orderBy('published_date', 'desc')->paginate(10);

        return ArticleResource::collection($articles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $this->authorize('create', Article::class);

        try {

            $data = $request->validated();
            $data['author_id'] = Auth::id();

            StoreArticleJob::dispatch($data);

            return response()->json(['message' => 'Article created successfully']);
        } catch (Throwable $e) {

            Log::error('StoreArticleJob failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $article = Article::where('id', $id)->with('categories', 'author')->first();

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $this->authorize('view', $article);

        return new ArticleResource($article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, $id)
    {
        $article = Article::where('id', $id)->first();

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $this->authorize('update', $article);

        $data = $request->validated();
        $data['author_id'] = Auth::id();

        UpdateArticleJob::dispatch($article, $data);

        return response()->json(['message' => 'Article updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article = Article::where('id', $id)->first();

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $this->authorize('delete', $article);

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully']);
    }
}
