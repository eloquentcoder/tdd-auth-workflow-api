<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    public function isFeatured(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value == 0 ? false : true
        );
    }

    public function scopeFeatured($query)
    {
        $query->where('is_featured', true);
    }

    /**
     * Get the category that owns the Product
     *
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
