<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleFilter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        return $query->when($this->request->filled('category_id'), function ($q) {
            $q->whereHas('categories', function ($subQuery) {
                $subQuery->where('categories.id', $this->request->category_id);
            });
        })->when($this->request->filled('status'), function ($q) {
            $q->where('status', $this->request->status);
        })->when($this->request->filled('from'), function ($q) {
            $q->where('published_date', '>=', $this->request->from);
        })->when($this->request->filled('to'), function ($q) {
            $q->where('published_date', '<=', $this->request->to);
        });
    }
}
