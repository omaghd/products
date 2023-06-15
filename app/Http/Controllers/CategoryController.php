<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Category\ICategoryRepository;
use Illuminate\Http\JsonResponse;

class CategoryController extends ApiController
{
    public function __construct(protected ICategoryRepository $categoryRepository)
    {
    }

    public function index()
    {
        return $this->successResponse(data: $this->categoryRepository->all());
    }

    public function store(StoreCategoryRequest $request): Category|JsonResponse
    {
        try {
            return $this->categoryRepository->create($request->validated());
        } catch (\Exception) {
            return $this->errorResponse('Something went wrong.', 500);
        }
    }

    public function show(string $id)
    {
        try {
            return $this->successResponse(data: $this->categoryRepository->find($id));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        try {
            return $this->categoryRepository->update($id, $request->validated());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->categoryRepository->delete($id);

            return $this->successResponse('Category deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
