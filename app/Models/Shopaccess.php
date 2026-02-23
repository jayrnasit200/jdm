<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shopaccess extends Model
{
    protected $table = 'shop_access';

    protected $fillable = [
        'shop_id',
        'seller_id'
    ];

    public function shop()
{
    return $this->belongsTo(Shop::class, 'shop_id');
}


}
