<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory;

    // Add 'rating' to the fillable property
    protected $fillable = [
        'name',
        'desc',
        'price',
        'rating',  // <-- Add 'rating' here
        'created_at',
        'updated_at',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_product')
            ->withPivot('quantity');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'product_promotion');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
