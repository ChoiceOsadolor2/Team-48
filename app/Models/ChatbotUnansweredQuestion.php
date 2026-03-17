<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotUnansweredQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'normalized_message',
        'detected_intents',
        'match_score',
        'recognized_products',
    ];

    protected $casts = [
        'detected_intents' => 'array',
        'recognized_products' => 'array',
    ];
}
