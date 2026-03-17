<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

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

    public function wishlistedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlist_items')
            ->withTimestamps();
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

    public function inventoryStockValues(): Collection
    {
        if ($this->hasPlatformSpecificStock()) {
            $stocks = $this->relationLoaded('platformStocks')
                ? $this->platformStocks
                : $this->platformStocks()->get();

            return $stocks->map(fn (ProductPlatformStock $stock) => (int) $stock->stock)->values();
        }

        return collect([(int) $this->stock]);
    }

    public function inventoryWorstStockValue(): int
    {
        return (int) $this->inventoryStockValues()->min();
    }

    public function inventoryStatusKey(): string
    {
        $stocks = $this->inventoryStockValues();

        if ($stocks->contains(fn (int $stock) => $stock <= 0)) {
            return 'out_of_stock';
        }

        if ($stocks->contains(fn (int $stock) => $stock <= 5)) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function inventoryStatusLabel(): string
    {
        return match ($this->inventoryStatusKey()) {
            'out_of_stock' => 'Out of stock',
            'low_stock' => 'Needs restock',
            default => 'Healthy stock',
        };
    }

    public function outOfStockPlatformCount(): int
    {
        return $this->inventoryStockValues()
            ->filter(fn (int $stock) => $stock <= 0)
            ->count();
    }

    public function lowStockPlatformCount(): int
    {
        return $this->inventoryStockValues()
            ->filter(fn (int $stock) => $stock > 0 && $stock <= 5)
            ->count();
    }

    public function inventorySummaryText(): string
    {
        if (! $this->hasPlatformSpecificStock()) {
            return (int) $this->stock . ' units';
        }

        $outCount = $this->outOfStockPlatformCount();
        $lowCount = $this->lowStockPlatformCount();
        $platformCount = $this->inventoryStockValues()->count();

        $formatPlatforms = static fn (int $count, string $suffix): string => $count . ' platform' . ($count === 1 ? '' : 's') . ' ' . $suffix;

        return match ($this->inventoryStatusKey()) {
            'out_of_stock' => implode(', ', array_filter([
                $outCount > 0 ? $formatPlatforms($outCount, 'out') : null,
                $lowCount > 0 ? $formatPlatforms($lowCount, 'low') : null,
            ])),
            'low_stock' => $formatPlatforms($lowCount, 'low'),
            default => $formatPlatforms($platformCount, 'available'),
        };
    }
}
