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

    // Relationship to order products
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'orders_id', 'id');
    }

// app/Models/Order.php
public function shop()
{
    return $this->belongsTo(Shop::class, 'shop_id', 'id');
}

}
