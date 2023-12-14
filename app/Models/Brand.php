<?php

namespace App\Models;

use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, BasicAudit;

    public $fillable = ["name", "description"];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
