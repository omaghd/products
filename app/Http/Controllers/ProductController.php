<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Product\IProductRepository;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;

class ProductController extends ApiController
{
    public function __construct(
        protected IProductRepository $productRepository,
        protected FileUploadService  $fileUploadService
    )
    {
    }

    public function index()
    {
        return $this->successResponse(data: $this->productRepository->all());
    }

    public function store(StoreProductRequest $request): Product|JsonResponse
    {
        try {
            $product = $this->productRepository->create($request->except('categories', 'image'));

            if ($request->hasFile('image')) {
                $publicPath = $this->fileUploadService->uploadFile($request->file('image'), 'public/products');
                $this->saveImage($product, $publicPath);
            }

            $this->productRepository->syncCategories($product->id, $request->get('categories'));

            return $this->successResponse("Product created successfully.", $product->fresh(), 201);
        } catch (\Exception) {
            return $this->errorResponse('Something went wrong.', 500);
        }
    }

    private function saveImage(Product $product, string $path)
    {
        $this->productRepository->update(
            $product->id,
            ['image' => $path]
        );
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = $this->productRepository->update($id, $request->except('categories'));

            if ($request->hasFile('image')) {
                $publicPath = $this->fileUploadService->uploadFile($request->file('image'), 'public/products');
                $this->saveImage($product, $publicPath);
            }

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
