<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'scheduled_to',
        'charged_at',
        'error',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'receiver_id');
    }

    public function isScheduled() : bool
    {
        return $this->scheduled_to !== null;
    }
}
