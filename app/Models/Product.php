<?php

namespace App\Models;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Productimg;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model

{
    protected $primaryKey = 'id';
    use HasFactory;
    protected $fillable = [
        'seller_id',
        "product_name",
        "category",
        "price",
        "stock",
        "weight",
        "description",
        "status",
        "type"
    ];

    protected $casts = [
        'type' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(Productimg::class,'product_id');
    }

    //for orders table relation
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }

    public function cart(){
        return $this->hasMany(Cart::class,'product_id');
    }

}
