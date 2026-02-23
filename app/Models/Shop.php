<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops'; // match your table name

    protected $fillable = [
        'company_name',
        'ref',
        'shopname',
        'address',
        'city',
        'postcode',
        'email',
        'phone',
        'Vat',
        'Name_staff',
        'Staffnumber1',
        'Staffnumber2',
    ];

    // 🧠 This is the missing relationship
    public function orders()
    {
        return $this->hasMany(Order::class, 'shop_id', 'id');
    }
    public function shopAccess()
    {
        return $this->hasMany(Shopaccess::class, 'shop_id');
    }
}
