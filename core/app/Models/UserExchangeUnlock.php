<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExchangeUnlock extends Model
{
    protected $table = 'user_exchange_unlocks';

    protected $fillable = [
        'user_id',
        'gateway_id',
        'method',
        'trx_id',
        'payment_proof',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gateway()
    {
        return $this->belongsTo(PaymentPopupGateway::class, 'gateway_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
