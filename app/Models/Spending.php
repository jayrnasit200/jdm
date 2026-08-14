<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    protected $table = 'spending';
    protected $fillable = [
        'Date','amount','user_id','description','created_at','updated_at',
      ];
}
