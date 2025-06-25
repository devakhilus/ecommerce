<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',      // e.g., 'SAVE10'
        'discount',  // e.g., 10.00
        'type',      // e.g., 'percent' or 'fixed'
        'expires_at' // optional
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
