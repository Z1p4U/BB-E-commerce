<?php

namespace App\Models;

use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, BasicAudit;

    public $fillable = ["name", "product_id", "size", "sale", "price", "discount_price", "description", "photo"];


    public function product()
    {
        $this->belongsTo(Category::class);
    }
}
