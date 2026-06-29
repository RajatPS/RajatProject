<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Stationery', 'description' => 'Pens, pencils, notebooks, erasers, and other writing supplies'],
            ['name' => 'Books', 'description' => 'Textbooks, notebooks, and reading materials'],
            ['name' => 'Electronics', 'description' => 'Electronic devices, gadgets, and tech accessories'],
            ['name' => 'Groceries', 'description' => 'Food items, beverages, and grocery products'],
            ['name' => 'Tools', 'description' => 'Hand tools, power tools, and hardware'],
            ['name' => 'Agriculture', 'description' => 'Agricultural products and farming supplies'],
            ['name' => 'Clothing', 'description' => 'Apparel, accessories, and fashion items'],
            ['name' => 'Home & Kitchen', 'description' => 'Household items, kitchen tools, and home appliances'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'slug' => Str::slug($category['name']),
                'active' => true,
            ]);
        }
    }
}
