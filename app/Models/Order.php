<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'sellar_id',
        'shop_id',
        'invoice_number',
        'comments_about_your_order',
        'invoice',
        'discount',
        'net_total',
        'Vat',
        'total',
        'payment_status',
    ];

    // Each order belongs to one shop
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    // Optional: each order belongs to one seller (if you have a Seller model)
    public function seller()
    {
        return $this->belongsTo(User::class, 'sellar_id');
    }
    public function orders()
{
    return $this->hasMany(Order::class, 'shop_id', 'id');
}

}
