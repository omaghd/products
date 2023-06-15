<?php

namespace App\Jobs;

use App\Models\Product;
use App\Repositories\Product\IProductRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveProductImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Product      $product,
        protected UploadedFile $image,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $productRepository = app(IProductRepository::class);

        $savePath  = "public/products/{$this->product->id}";
        $finalPath = str_replace(
            'public',
            'storage',
            $this->image->storePublicly($savePath)
        );

        $productRepository->update(
            $this->product->id,
            ['image' => $finalPath]
        );
    }
}
