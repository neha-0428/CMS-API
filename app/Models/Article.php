<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{

    use SoftDeletes;

    const PUBLISHED = "Published";
    const DRAFT = "Draft";
    const ARCHIVED = "Archived";

    protected $casts = [
        'published_date' => 'date'
    ];

    protected $fillable = ['title', 'slug', 'content', 'summary', 'status', 'published_date', 'author_id'];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
