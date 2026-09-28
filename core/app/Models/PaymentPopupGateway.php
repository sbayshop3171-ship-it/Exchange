<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPopupGateway extends Model
{
    protected $table = 'payment_popup_gateways';

    protected $fillable = [
        'name',
        'symbol',
        'network',
        'wallet_address',
        'qr_code_image',
        'is_trx_required',
        'is_proof_required',
        'unlock_fee_amount',
        'fee_currency',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_trx_required' => 'boolean',
        'is_proof_required' => 'boolean',
        'unlock_fee_amount' => 'decimal:8',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function unlockRequests()
    {
        return $this->hasMany(UserExchangeUnlock::class, 'gateway_id');
    }
}
