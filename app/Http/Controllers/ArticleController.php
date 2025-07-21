<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Jobs\Article\StoreArticleJob;
use App\Jobs\Article\UpdateArticleJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all();

        return response()->json(['data' => $articles]);
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
        try {
            $data = $request->validated();

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
        $article = Article::where('id', $id)->first();
        if (!$article) {
            return response()->json(['message' => 'Article not found']);
        }

        return response()->json(['data' => $article]);
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
            return response()->json(['message' => 'Article not found']);
        }

        $data = $request->validated();

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
            return response()->json(['message' => 'Article not found']);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully']);
    }
}
