<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSubscriptionPlan extends Model
{
    protected $fillable = ['name', 'duration_days', 'amount', 'currency', 'status'];

    protected $casts = [
        'duration_days' => 'integer',
        'amount' => 'decimal:8',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
