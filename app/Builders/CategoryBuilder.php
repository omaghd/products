<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class CategoryBuilder extends Builder
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

    public function withProducts(): self
    {
        return $this->with('products');
    }

    public function withParent(): self
    {
        return $this->with('parent');
    }

    public function withChildren(): self
    {
        return $this->with('children');
    }

    public function withDescendants(): self
    {
        return $this->with('descendants');
    }

    public function withProductsCount(): self
    {
        return $this->withCount('products');
    }

    public function withChildrenCount(): self
    {
        return $this->withCount('children');
    }
}
