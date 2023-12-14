<?php

namespace App\Models;

use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, BasicAudit;

    public $fillable = ["name", "short_description", "description", "category_id", "brand_id", "photo"];


    public function product()
    {
        $this->belongsTo(Category::class);
    }
}
