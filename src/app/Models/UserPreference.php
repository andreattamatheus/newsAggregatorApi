<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPreference extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'preference_id', 'content'];

    protected $casts = [
        'content' => 'array',
    ];

    public function preference(): BelongsTo
    {
        return $this->belongsTo(Preference::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPreference($query, $preferenceId)
    {
        return $query->where('preference_id', $preferenceId);
    }

    public function scopeByType($query, $type)
    {
        return $query->whereHas('preference', function ($query) use ($type) {
            $query->where('type', $type);
        });
    }
}
