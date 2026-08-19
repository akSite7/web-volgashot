<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductImageController extends Controller
{
    public function show(Product $product): BinaryFileResponse
    {
        abort_unless(
            $product->photo_path && Storage::disk('public')->exists($product->photo_path),
            404,
        );

        return response()->file(Storage::disk('public')->path($product->photo_path), [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
