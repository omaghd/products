<?php

namespace App\Console\Commands;

use App\Console\Traits\AskAndValidate;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\Product\IProductRepository;
use Illuminate\Console\Command;

class CreateProduct extends Command
{
    use AskAndValidate;

    protected $signature = 'product:create';

    protected $description = 'Create a new product';

    public function handle(): void
    {
        $productRepository = app(IProductRepository::class);

        $name        = $this->askAndValidate('What is the product name?', 'required|string|max:255|unique:products,name', 'name');
        $description = $this->askAndValidate('What is the product description?', 'required|string', 'description');
        $price       = $this->askAndValidate('What is the product price?', 'required|numeric|min:0', 'price');
        $imageURL    = $this->askAndValidate('What is the product image URL?', 'nullable|url', 'image');

        $categoryIds = $this->getCategoryIds();

        $product = $productRepository->create([
            'name'        => $name,
            'description' => $description,
            'price'       => $price,
            'image_url'   => $imageURL,
        ]);

        $productRepository->syncCategories($product['id'], $categoryIds);

        $this->info("Product \"{$product['name']}\" created successfully!");
    }

    private function getCategoryIds(): array
    {
        $categories = app(ICategoryRepository::class)->all();

        foreach ($categories as $category) {
            $this->info("[{$category['id']}]: {$category['name']}");
        }

        $categoryIds = $this->ask('What are the category IDs? (comma separated)');

        return $this->validateCategoryIds($categories, $categoryIds);
    }

    private function validateCategoryIds(array $categories, ?string $categoryIds): array
    {
        if (empty($categoryIds)) {
            $this->error('You must provide at least one category ID.');

            return $this->getCategoryIds();
        }

        $categoryIds = explode(',', $categoryIds);

        foreach ($categoryIds as $categoryId) {
            $category = collect($categories)->firstWhere('id', $categoryId);

            if (empty($category)) {
                $this->error("Category ID {$categoryId} is invalid.");

                return $this->getCategoryIds();
            }
        }

        return $categoryIds;
    }
}
