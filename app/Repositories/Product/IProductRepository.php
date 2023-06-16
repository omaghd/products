<?php

namespace App\Repositories\Product;

use App\Models\Product;

interface IProductRepository
{
    public function all(): array;

    public function find(int $id): Product;

    public function findByName(string $name): ?Product;

    public function create(array $data): Product;

    public function syncCategories(int $id, array $categoryIds): void;

    public function update(int $id, array $data): Product;

    public function delete(int $id): void;
}
