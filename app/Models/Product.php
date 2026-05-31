<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = ['name', 'description', 'price', 'category_id', 'image', 'stock', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
