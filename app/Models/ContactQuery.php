<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function scopeContactFormOnly(Builder $query): Builder
    {
        return $query->where(function (Builder $inner) {
            $inner->whereNull('subject')
                ->orWhere('subject', 'not like', 'Refund request - %');
        });
    }
}
