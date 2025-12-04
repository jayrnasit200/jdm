<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'shop',
        'products',
        'categories',
        'discounts',
    ];

    // If you want, you can cast to bool
    protected $casts = [
        'shop'       => 'boolean',
        'products'   => 'boolean',
        'categories' => 'boolean',
        'discounts'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
