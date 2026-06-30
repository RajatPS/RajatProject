<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory;
    protected $fillable=[
        'name',
        'email',
        'phone_number',
        'address',
        'DOB',
        'gender',
        'password',
        'role',
        'account_status',
        'assigned_area',
        'vehicle_type',
        'vehicle_no',
        'license_no',
        ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function cart(){
        return $this->hasMany(Cart::class,'user_id','id');
    }
}

 