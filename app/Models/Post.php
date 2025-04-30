<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Post extends Model
{
    //
    use Sluggable, HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        // 'tags',
        'meta_keywords',
        'meta_description',
        'visibility',
        'category',
        'user_id'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function post_category() {
        return $this->hasOne(Category::class, 'id', 'category');
    }
    public function scopeSearch($query, $term) {
        $term = "%$term%";
        $query->where(function($query) use ($term) {
            $query->where('title','like', $term);
        });
    }
}
