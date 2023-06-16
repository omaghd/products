<?php

namespace App\Console\Commands;

use App\Console\Traits\AskAndValidate;
use App\Http\Requests\Product\StoreProductRequest;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\Product\IProductRepository;
use App\Services\FileUploadService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class CreateProduct extends Command
{
    use AskAndValidate;

    protected $signature = 'product:create';

    protected $description = 'Create a new product';

    protected StoreProductRequest $request;

    public function __construct()
    {
        parent::__construct();

        $this->request = new StoreProductRequest();
    }

    public function handle(): void
    {
        $name        = $this->askAndValidate('What is the product name?', 'name', $this->request);
        $description = $this->askAndValidate('What is the product description?', 'description', $this->request);
        $price       = $this->askAndValidate('What is the product price?', 'price', $this->request);

        $image = $this->handleImage();

        $categoryIds = $this->getCategoryIds();

        $image = $image ? $this->getImagePublicPath($image) : null;

        $productRepository = app(IProductRepository::class);

        $productData = [
            'name'        => $name,
            'description' => $description,
            'price'       => $price,
        ];
        if ($image) $productData['image'] = $image;
        $product = $productRepository->create($productData);

        $productRepository->syncCategories($product['id'], $categoryIds);

        $this->info("Product \"{$product['name']}\" created successfully!");
    }

    private function handleImage(): ?UploadedFile
    {
        $imagePath = $this->validatePath();
        return $imagePath ? $this->validateImage($imagePath) : null;
    }

    private function validatePath(): ?string
    {
        $imagePath = $this->ask('What is the product image path wrapped in double quotes? (optional)');

        if (empty($imagePath)) return '';

        if (!str_starts_with($imagePath, '"') || !str_ends_with($imagePath, '"')) {
            $this->error('The image path must be wrapped in double quotes.');

            return $this->handleImage();
        }

        $imagePath = str_replace('"', '', $imagePath);

        if (!file_exists($imagePath)) {
            $this->error("Image in path \"{$imagePath}\" does not exist.");

            return $this->handleImage();
        }

        return $imagePath;
    }

    private function validateImage(string $imagePath): string|UploadedFile|null
    {
        $image = new UploadedFile($imagePath, basename($imagePath));

        $rules = $this->request->rules()['image'];

        $validator = Validator::make(
            ['image' => $image],
            ['image' => $rules]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return $this->handleImage();
        }

        return $image;
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

        if(count($categoryIds) !== count(array_unique($categoryIds))){
            $this->error("You can't provide duplicate category IDs.");

            return $this->getCategoryIds();
        }

        return $categoryIds;
    }

    private function getImagePublicPath(UploadedFile $image): string
    {
        $fileUploadService = app(FileUploadService::class);

        return $fileUploadService->uploadFile($image, 'public/products');
    }
}
