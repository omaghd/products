<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Product\IProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ProductController extends ApiController
{
    public function __construct(protected IProductRepository $productRepository)
    {
    }

    public function index()
    {
        return $this->successResponse(data: $this->productRepository->all());
    }

    public function store(StoreProductRequest $request): Product|JsonResponse
    {
        try {
            $product = $this->productRepository->create($request->except('categories'));

            if ($request->hasFile('image'))
                $this->saveImage($product, $request->file('image'));

            $this->productRepository->syncCategories($product->id, $request->get('categories'));

            return $product->fresh();
        } catch (\Exception) {
            return $this->errorResponse('Something went wrong.', 500);
        }
    }

    private function saveImage(Product $product, UploadedFile $image)
    {
        $savePath  = "public/products/{$product->id}";
        $finalPath = str_replace(
            'public',
            'storage',
            $image->storePublicly($savePath)
        );

        $this->productRepository->update(
            $product->id,
            ['image' => $finalPath]
        );
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = $this->productRepository->update($id, $request->except('categories'));

            if ($request->hasFile('image'))
                $this->saveImage($product, $request->image);

            $this->productRepository->syncCategories($product->id, $request->get('categories'));

            return $product->fresh();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function show(string $id)
    {
        try {
            return $this->successResponse(data: $this->productRepository->find($id));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->productRepository->delete($id);

            return $this->successResponse('Product deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
