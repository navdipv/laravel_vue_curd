<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $appends = ['image_url'];
    public function categories()
    {
        return $this->hasMany(ProductCategory::class, 'product_id');
    }
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }
        return asset('images/no-image.png');
    }

}
