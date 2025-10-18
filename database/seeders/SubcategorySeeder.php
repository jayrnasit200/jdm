<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    public function run()
    {
        $subcategories = [
            // HALLOWEEN 2025 (ID: 1)
            ['id' => 101, 'categories_id' => 1, 'name' => 'Seasonal Candy Corn'],
            ['id' => 102, 'categories_id' => 1, 'name' => 'Seasonal Mellowcreme Items (Pumpkins, Leaves)'],
            ['id' => 103, 'categories_id' => 1, 'name' => 'Seasonal Chewy Caramels'],
            ['id' => 104, 'categories_id' => 1, 'name' => 'Seasonal Bagged Chocolate Items (Kisses, Minis)'],

            // SPECIAL (SEASONINGS & SAUCES) (ID: 2)
            ['id' => 201, 'categories_id' => 2, 'name' => 'Cajun Dry Seasonings & Rubs'],
            ['id' => 202, 'categories_id' => 2, 'name' => 'Cajun Hot Sauces'],
            ['id' => 203, 'categories_id' => 2, 'name' => 'Cajun Meal Mixes & Cooking Bases'],

            // GIFT SETS (ID: 3)
            ['id' => 301, 'categories_id' => 3, 'name' => 'Gourmet Tea Samplers & Blends'],
            ['id' => 302, 'categories_id' => 3, 'name' => 'Specialty Hot Sauce Collections'],
            ['id' => 303, 'categories_id' => 3, 'name' => 'Gourmet Cocoa & Hot Beverage Collections'],

            // NIBBLEZ (SPECIALTY CHIPS) (ID: 4)
            ['id' => 401, 'categories_id' => 4, 'name' => 'Processed Salmon Chips'],
            ['id' => 402, 'categories_id' => 4, 'name' => 'Cassava Vegetable Chips'],
            ['id' => 403, 'categories_id' => 4, 'name' => 'Other Specialty Savory Snacks'],

            // SAUCES, DIPS, & TOPPINGS (ID: 5)
            ['id' => 501, 'categories_id' => 5, 'name' => 'Dessert Topping Syrups (Chocolate, Caramel)'],
            ['id' => 502, 'categories_id' => 5, 'name' => 'Savory Salad Dressings (Caesar, Ranch)'],
            ['id' => 503, 'categories_id' => 5, 'name' => 'Sweet Dessert Dips & Spreads'],
            ['id' => 504, 'categories_id' => 5, 'name' => 'Cooking & Dipping Sauces (Non-Cajun)'],

            // RAINFOREST WATER (ID: 6)
            ['id' => 601, 'categories_id' => 6, 'name' => 'Single-Serve Bottled Water (500ml/1L)'],
            ['id' => 602, 'categories_id' => 6, 'name' => 'Bulk Bottled Water (Multi-Pack/Larger Volume)'],

            // BAKING (ID: 7)
            ['id' => 701, 'categories_id' => 7, 'name' => 'Cake & Cookie Frostings'],
            ['id' => 702, 'categories_id' => 7, 'name' => 'Dry Baking Mixes (Cakes, Brownies, Breads)'],
            ['id' => 703, 'categories_id' => 7, 'name' => 'Pie Crusts & Related Products'],
            ['id' => 704, 'categories_id' => 7, 'name' => 'Baking Chocolate & Morsels'],
            ['id' => 705, 'categories_id' => 7, 'name' => 'Leavening Agents (Baking Soda, Powder)'],

            // PANCAKE & WAFFLE (ID: 8)
            ['id' => 801, 'categories_id' => 8, 'name' => 'Pancake/Waffle Dry Mixes'],
            ['id' => 802, 'categories_id' => 8, 'name' => 'Pancake/Waffle Table Syrups (Maple, Fruit)'],
            ['id' => 803, 'categories_id' => 8, 'name' => 'Frozen Waffles & Pancakes'],

            // BREAKFAST CEREALS (ID: 9)
            ['id' => 901, 'categories_id' => 9, 'name' => 'Kids Cereal (Sweetened/Character)'],
            ['id' => 902, 'categories_id' => 9, 'name' => 'Adult Cereal (Plain/Fiber/Granola)'],
            ['id' => 903, 'categories_id' => 9, 'name' => 'Oatmeal & Hot Cereals (Plain, Flavored)'],
            ['id' => 904, 'categories_id' => 9, 'name' => 'Cereal Bars & Grab-N-Go'],

            // SNACKS (GENERAL) (ID: 10)
            ['id' => 1001, 'categories_id' => 10, 'name' => 'Savory Crisps & Potato Chips'],
            ['id' => 1002, 'categories_id' => 10, 'name' => 'Trail Mixes & Nuts'],
            ['id' => 1003, 'categories_id' => 10, 'name' => 'Popcorn (Microwave & Ready-to-Eat)'],
            ['id' => 1004, 'categories_id' => 10, 'name' => 'Packaged Fruit Snacks & Roll-ups'],
            ['id' => 1005, 'categories_id' => 10, 'name' => 'Jerky & Meat Snacks'],

            // BEVERAGE FLAVORING SYRUPS (ID: 11)
            ['id' => 1101, 'categories_id' => 11, 'name' => 'Coffee Flavoring Syrups (Sugar-Free)'],
            ['id' => 1102, 'categories_id' => 11, 'name' => 'Coffee Flavoring Syrups (Regular)'],
            ['id' => 1103, 'categories_id' => 11, 'name' => 'Cocktail & Bar Mixers/Syrups'],
            ['id' => 1104, 'categories_id' => 11, 'name' => 'Water Enhancers & Infusions'],

            // SOFT DRINKS & JUICES (ID: 12)
            ['id' => 1201, 'categories_id' => 12, 'name' => 'Carbonated Soft Drinks (Canned/Bottled)'],
            ['id' => 1202, 'categories_id' => 12, 'name' => 'Shelf-Stable Juices & Nectars'],
            ['id' => 1203, 'categories_id' => 12, 'name' => 'Sports & Energy Drinks'],
            ['id' => 1204, 'categories_id' => 12, 'name' => 'Ready-to-Drink Teas & Coffees'],

            // CANDY & CONFECTIONERY (ID: 13)
            ['id' => 1301, 'categories_id' => 13, 'name' => 'Year-Round Chewy Candy (Gummies, Licorice)'],
            ['id' => 1302, 'categories_id' => 13, 'name' => 'Hard Candy & Mints'],
            ['id' => 1303, 'categories_id' => 13, 'name' => 'Everyday Bagged Chocolate'],
            ['id' => 1304, 'categories_id' => 13, 'name' => 'Chewing Gum & Bubble Gum'],
        ];

        DB::table('subcategories')->insert($subcategories);
    }
}
