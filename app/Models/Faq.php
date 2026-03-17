<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'general' => 'General',
        'shipping' => 'Shipping',
        'returns' => 'Returns',
        'orders' => 'Orders',
        'account' => 'Account',
        'payment' => 'Payment',
        'stock' => 'Stock & Availability',
        'recommendations' => 'Recommendations',
        'account_deletion' => 'Account Deletion',
        'opening_hours' => 'Opening Hours',
        'contact' => 'Contact',
    ];

    protected $fillable = ['keyword', 'answer', 'category', 'priority'];
}
