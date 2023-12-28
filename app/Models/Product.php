<?php

namespace App\Models;

use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, BasicAudit;

    public $fillable = ["name", "short_description", "description", "photo"];

    // public $appends = ["category_name", "brand_name"];

    // protected function getCategoryNameAttribute()
    // {
    //     $category = Category::where(['id' => $this->attributes['category_ids']])->first();

    //     if ($category) {
    //         return $category->name;
    //     } else {
    //         return null;
    //     }
    // }

    // protected function getBrandNameAttribute()
    // {
    //     $brand = Brand::where(['id' => $this->attributes['brand_ids']])->first();

    //     if ($brand) {
    //         return $brand->name;
    //     } else {
    //         return null;
    //     }
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class, "category_product");
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, "brand_product");
    }

    public function items()
    {
        $this->hasMany(Item::class);
    }
}
