<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use App\Models\ProductImg;
use App\Models\User;

class Order extends Model
{
    
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'staff_id',
        'fullname',
        'contact_number',
        'email',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'paymentMethod',
        'quantity',
        'totalAmount',
        'product_id',
        'cardName',
        'cardNumber',
        'expmonth',
        'expyear',
        'cvv',
        'upi',
        'status',  
        'order_date',
        'order_time',                                                                                                                  

    ];  

    protected $casts = [
        'order_date' => 'date',
        'order_time' => 'datetime',
    ];

    public function user(){
       return $this->belongsTo(User::class, 'user_id', 'id'); 
    }

    // Each order belongs to one product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Fetch product images via the product relationship
    public function productImages()
    {
        return $this->hasManyThrough(
            ProductImg::class, // Final model
            Product::class,    // Intermediate model
            'id',              // Local key on products table
            'product_id',      // Foreign key on product_imgs table
            'product_id',      // Local key on orders table
            'id'               // Local key on products table
        );
    }
}
