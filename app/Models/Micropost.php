<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Micropost extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'micropost_id', 'user_id');
    }

    public function votes()
    {
        return $this->belongsToMany(Vote::class, 'microposts_id');
    }
}
