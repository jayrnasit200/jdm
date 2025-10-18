<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_number',
        'name',
        'categories_id',
        'subcategories_id',
        'description',
        'image',
        'backimage',
        'nutritionimage',
        'price',
        'barcode',
        'vat',
        'status',
        'special_offer'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function subcategory() {
        return $this->belongsTo(Subcategory::class, 'subcategories_id');
    }
}
