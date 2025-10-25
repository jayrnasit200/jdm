<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops'; // match your table name

    protected $fillable = [
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
}
