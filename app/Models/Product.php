<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nette\Utils\Image;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'category_id', 'description', 'price', 'stock','status','unit'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->oldest('id','ASC');
    }

}
