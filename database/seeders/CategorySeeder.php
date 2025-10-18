<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['id' => 1, 'name' => 'HALLOWEEN 2025'],
            ['id' => 2, 'name' => 'SPECIAL (SEASONINGS & SAUCES)'],
            ['id' => 3, 'name' => 'GIFT SETS'],
            ['id' => 4, 'name' => 'NIBBLEZ (SPECIALTY CHIPS)'],
            ['id' => 5, 'name' => 'SAUCES, DIPS, & TOPPINGS'],
            ['id' => 6, 'name' => 'RAINFOREST WATER'],
            ['id' => 7, 'name' => 'BAKING'],
            ['id' => 8, 'name' => 'PANCAKE & WAFFLE'],
            ['id' => 9, 'name' => 'BREAKFAST CEREALS'],
            ['id' => 10, 'name' => 'SNACKS (GENERAL)'],
            ['id' => 11, 'name' => 'BEVERAGE FLAVORING SYRUPS'],
            ['id' => 12, 'name' => 'SOFT DRINKS & JUICES'],
            ['id' => 13, 'name' => 'CANDY & CONFECTIONERY'],
        ];

        DB::table('categories')->insert($categories);
    }
}
