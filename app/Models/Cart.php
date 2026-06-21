<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Cart extends Model
{
    protected $table = 'carts';
    // protected $primaryKey = 'id';

    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];
    public $timestamps =false;


    //relationships

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}


    
    