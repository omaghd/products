<?php

namespace App\Repositories\Category;

use App\Models\Category;

class CategoryRepository implements ICategoryRepository
{
    public function all(): array
    {
        return Category::query()
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
        return Category::findOrFail($id);
    }

    public function delete(int $id): void
    {
        $category = $this->find($id);
        $category->delete();
    }
}
