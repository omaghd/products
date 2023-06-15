<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository implements IProductRepository
{
    public function all(): array
    {
        return Product::query()
            ->search()
            ->sortByPrice()
            ->filterByCategory()
            ->withCategories()
            ->paginate(5)
            ->toArray();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function syncCategories(int $id, array $categoryIds): void
    {
        $product = $this->find($id);
        $product->categories()->sync($categoryIds);
    }

    public function find(int $id): Product
    {
        try {
            return Product::findOrFail($id);
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException('Product not found.');
        }
    }

    public function update(int $id, array $data): Product
    {
        $category = $this->find($id);
        $category->update($data);

        return $category;
    }

    public function delete(int $id): void
    {
        $category = $this->find($id);
        $category->delete();
    }
}
