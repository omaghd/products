<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryRepository implements ICategoryRepository
{
    public function all(): array
    {
        return Category::query()
            ->search()
            ->withParent()
            ->withChildren()
            ->withDescendants()
            ->withProducts()
            ->withProductsCount()
            ->withChildrenCount()
            ->paginate()
            ->toArray();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->find($id);
        $category->update($data);

        return $category;
    }

    public function find(int $id): Category
    {
        try {
            return Category::findOrFail($id);
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException('Category not found.');
        }
    }

    public function delete(int $id): void
    {
        $category = $this->find($id);
        $category->delete();
    }
}
