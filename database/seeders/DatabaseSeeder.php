<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory()
            ->count(5)
            ->create()
            ->each(function (Category $category): void {
                $category
                    ->children()
                    ->saveMany(
                        Category::factory()
                            ->count(3)
                            ->create()
                    );
            });

        $categories = Category::all();

        Product::factory()
            ->count(30)
            ->create()
            ->each(function (Product $product) use ($categories): void {
                $product
                    ->categories()
                    ->attach(
                        $categories
                            ->random(rand(1, 3))
                            ->pluck('id')
                            ->toArray()
                    );
            });
    }
}
