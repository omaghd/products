<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ProductBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    public function search(): self
    {
        return $this->when(
            request('search'),
            fn($query) => $query->where(
                'name',
                'like',
                '%' . request('search') . '%'
            )
        );
    }

    public function findByName(string $name): self
    {
        return $this->where('name', $name);
    }

    public function sortByPrice(): self
    {
        return $this->when(
            request('sort'),
            fn($query) => $query->orderBy('price', request('sort'))
        );
    }

    public function filterByCategory(): self
    {
        return $this->when(
            request('category'),
            fn($query) => $query
                ->whereHas(
                    'categories',
                    fn($query) => $query->where('id', request('category'))
                )
        );
    }

    public function withCategories(): self
    {
        return $this->with('categories');
    }
}
