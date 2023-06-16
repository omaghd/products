<?php

namespace App\Console\Commands;

use App\Console\Traits\AskAndValidate;
use App\Http\Requests\Product\StoreProductRequest;
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

        $request = new StoreProductRequest();

        $name        = $this->askAndValidate('What is the product name?', 'name', $request);
        $description = $this->askAndValidate('What is the product description?', 'description', $request);
        $price       = $this->askAndValidate('What is the product price?', 'price', $request);
        $imageURL    = $this->askAndValidate('What is the product image URL?', 'image', $request);

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
