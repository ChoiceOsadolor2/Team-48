<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'date_of_birth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function refundRequests()
    {
        return $this->hasMany(\App\Models\RefundRequest::class);
    }

    public function serviceReviews()
    {
        return $this->hasMany(\App\Models\ServiceReview::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(\App\Models\WishlistItem::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'wishlist_items')
            ->withTimestamps();
    }
}
