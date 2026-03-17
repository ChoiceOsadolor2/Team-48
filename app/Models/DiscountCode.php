<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    public const TYPES = [
        'percentage' => 'Percentage',
        'fixed' => 'Fixed amount',
    ];

    protected $fillable = [
        'code',
        'type',
        'value',
        'is_active',
        'usage_limit',
        'used_count',
        'starts_at',
        'ends_at',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    public function hasStarted(): bool
    {
        return $this->starts_at === null || ! $this->starts_at->isFuture();
    }

    public function hasUsageRemaining(): bool
    {
        return $this->usage_limit === null || $this->used_count < $this->usage_limit;
    }

    public function availabilityLabel(): string
    {
        if (! $this->is_active) {
            return 'Inactive';
        }

        if (! $this->hasStarted()) {
            return 'Scheduled';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        if (! $this->hasUsageRemaining()) {
            return 'Limit reached';
        }

        return 'Active';
    }
}
