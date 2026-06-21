<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_review extends Model
{
    public $timestamps = false;
    protected $table = 'review';
    protected $fillable = [
        'user_id',
        'user_name',
        'product_id',
        'user_email',
        'review',
        'rating',
        'date',
        'time',
    ];
}
