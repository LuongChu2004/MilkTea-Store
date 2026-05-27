<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }
}
