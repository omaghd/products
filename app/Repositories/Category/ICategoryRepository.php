<?php

namespace App\Repositories\Category;

use App\Models\Category;

interface ICategoryRepository
{
    public function all(): array;

    public function find(int $id): Category;

    public function create(array $data): Category;

    public function update(int $id, array $data): Category;

    public function delete(int $id): void;
}
