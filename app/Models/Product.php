<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $appends = [
        'platform_stock_map',
    ];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'platform',
        'image_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function platformStocks(): HasMany
    {
        return $this->hasMany(ProductPlatformStock::class)->orderBy('platform');
    }

    public function getPlatformStockMapAttribute(): array
    {
        $stocks = $this->relationLoaded('platformStocks')
            ? $this->platformStocks
            : $this->platformStocks()->get();

        return $stocks
            ->mapWithKeys(fn (ProductPlatformStock $stock) => [
                $stock->platform => (int) $stock->stock,
            ])
            ->all();
    }

    public function hasPlatformSpecificStock(): bool
    {
        if ($this->relationLoaded('platformStocks')) {
            return $this->platformStocks->isNotEmpty();
        }

        return $this->platformStocks()->exists();
    }

    public function stockForPlatform(?string $platform = null): int
    {
        $normalizedPlatform = trim((string) $platform);

        if ($normalizedPlatform === '') {
            return (int) $this->stock;
        }

        $stocks = $this->relationLoaded('platformStocks')
            ? $this->platformStocks
            : $this->platformStocks()->get();

        $match = $stocks->firstWhere('platform', $normalizedPlatform);

        if ($match) {
            return (int) $match->stock;
        }

        return $stocks->isNotEmpty() ? 0 : (int) $this->stock;
    }
}
