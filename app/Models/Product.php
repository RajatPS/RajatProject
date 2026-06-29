<?php

namespace App\Models;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Productimg;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model

{
    protected $primaryKey = 'id';
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'seller_id',
        'category_id',
        "product_name",
        "brand",
        "category",
        "price",
        "stock",
        "weight",
        "description",
        "status",
        "type",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected $casts = [
        'type' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    public function images()
    {
        return $this->hasMany(Productimg::class,'product_id');
    }

    /**
     * Get the category of this product
     */
    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    //for orders table relation
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }

    public function cart(){
        return $this->hasMany(Cart::class,'product_id');
    }

    /**
     * Scope: Search products by name, brand, or category (case-insensitive)
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->whereRaw('LOWER(product_name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
              ->orWhereRaw('LOWER(brand) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
              ->orWhereHas('categoryRelation', function ($q) use ($searchTerm) {
                  $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
              });
        });
    }
}
