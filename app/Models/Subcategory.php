<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categories_id',
    ];
    public function products() {
        return $this->hasMany(Product::class, 'subcategories_id');
    }
}
