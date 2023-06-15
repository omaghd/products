<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->artisan('migrate:fresh --seed');
});

it('creates a new product via cli', function () {
    $this->artisan('product:create')
        ->expectsQuestion('What is the product name?', 'Test Product')
        ->expectsQuestion('What is the product description?', 'Test Product Description')
        ->expectsQuestion('What is the product price?', 149.99)
        ->expectsQuestion('What is the product image URL?', 'https://fakeimg.pl/500x500/cccccc/909090?text=YouCan')
        ->expectsQuestion('What are the category IDs? (comma separated)', '1,2')
        ->expectsOutput('Product "Test Product" created successfully!')
        ->assertExitCode(0);

    $this->assertDatabaseHas('products', [
        'name'        => 'Test Product',
        'description' => 'Test Product Description',
        'price'       => 149.99,
        'image'       => "https://fakeimg.pl/500x500/cccccc/909090?text=YouCan",
    ]);
});

it('creates a new product via api', function () {
    $image = UploadedFile::fake()->image('YouCan.jpg');

    $response = $this->postJson('/api/products', [
        'name'        => 'Test Product',
        'description' => 'Test Product Description',
        'price'       => 149.99,
        'image'       => $image,
        'categories'  => [1, 2],
    ]);

    $response->assertCreated();

    $productId = $response->json('data.id');
    Storage::disk('public')->assertExists("products/{$productId}/{$image->hashName()}");

    $this->assertDatabaseHas('products', [
        'name'        => 'Test Product',
        'description' => 'Test Product Description',
        'price'       => 149.99,
        'image'       => "storage/products/{$productId}/{$image->hashName()}",
    ]);

    $this->assertDatabaseHas('category_product', [
        'category_id' => 1,
        'product_id'  => $productId,
    ]);

    $this->assertDatabaseHas('category_product', [
        'category_id' => 2,
        'product_id'  => $productId,
    ]);
});
