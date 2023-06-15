<?php

namespace App\Models;

use App\Builders\ProductBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static ProductBuilder query()
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
