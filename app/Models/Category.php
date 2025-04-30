<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Sluggable, HasFactory;

    protected $fillable = ['name', 'slug', 'parent', 'order'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function parentCategory() {
        return $this->belongsTo(ParentCategory::class, 'parent','id');
    }

    public function posts() {
        return $this->hasMany(Post::class,'category','id');
    }
}
