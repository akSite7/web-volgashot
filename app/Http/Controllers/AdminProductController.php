<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('products', 'public');
        }
        unset($data['photo']);
        Product::create($data);

        return back()->with('success', 'Товар добавлен.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('photo')) {
            if ($product->photo_path) {
                Storage::disk('public')->delete($product->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('products', 'public');
        }
        unset($data['photo']);
        $product->update($data);

        return back()->with('success', 'Товар обновлён.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->photo_path) {
            Storage::disk('public')->delete($product->photo_path);
        }
        $product->delete();

        return back()->with('success', 'Товар удалён.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:shot,buckshot'],
            'description' => ['nullable', 'string', 'max:3000'],
            'price_per_kg' => ['required', 'integer', 'min:0', 'max:99999999'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);
    }
}
