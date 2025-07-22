<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "content" => $this->content,
            "summary" => $this->summary,
            "status" => $this->status,
            "published_date" => $this->published_date,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "author" => [
                "id" => $this->author->id ?? null,
                "name" => $this->author->name ?? null,
                "role_id" => $this->author->role_id
            ],
            "categories" => $this->categories->map(function ($category) {
                return [
                    "id" => $category->id,
                    "name" => $category->name,
                ];
            }),
        ];
    }
}
