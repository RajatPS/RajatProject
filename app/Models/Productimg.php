<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productimg extends Model
{
   use HasFactory;
//    protected $table = 'productimgs';
   protected $fillable = [
   "product_id",
   "image"
   ];

   public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    
    
}

