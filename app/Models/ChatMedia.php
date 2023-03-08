<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'title',
        'type',
        'path',
        'url',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
