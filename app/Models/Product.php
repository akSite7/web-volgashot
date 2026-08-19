<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'type', 'description', 'price_per_kg', 'photo_path'])]
class Product extends Model
{
    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return ['price_per_kg' => 'decimal:2'];
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path
            ? '/product-images/'.$this->getKey().'?v='.$this->updated_at?->timestamp
            : null;
    }
}
