<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentCategory extends Model
{
    //
    use Sluggable, HasFactory;

    protected $fillable = ['name', 'slug', 'order'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function categoria() {
        return $this->hasMany(Category::class, 'parent', 'id');
    }
}
