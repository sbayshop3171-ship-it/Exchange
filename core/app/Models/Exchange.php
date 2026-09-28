<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\CommonScope;
use App\Traits\FileExport;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use CommonScope, FileExport;

    protected $guarded = ['id'];

    protected $casts = [
        'user_data'               => 'object',
        'transaction_proof_data' => 'object',
        'charge'                  => 'object',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendCurrency()
    {
        return $this->belongsTo(Currency::class, 'send_currency_id');
    }

    public function receivedCurrency()
    {
        return $this->belongsTo(Currency::class, 'receive_currency_id');
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function scopeList($query)
    {
        return $query->whereIn('status', Status::EXCHANGE_ALL_STATUS);
    }

    public function scopeInitiated($query)
    {
        return $query->where('status', Status::EXCHANGE_INITIAL);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::EXCHANGE_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::EXCHANGE_APPROVED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', Status::EXCHANGE_CANCEL);
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', Status::EXCHANGE_REFUND);
    }

    public function badgeData($showTime = true)
    {
        $html = '';
        if ($this->status == Status::EXCHANGE_PENDING) {
            $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
        } elseif ($this->status == Status::EXCHANGE_APPROVED) {
            $html = '<span><span class="badge badge--success">' . trans('Approved') . '</span>';
            if ($showTime) $html .= '<br>' . diffForHumans($this->updated_at);
            $html .= '</span>';
        } elseif ($this->status == Status::EXCHANGE_CANCEL) {
            $html = '<span class="badge badge--danger">' . trans('Canceled') . '</span>';
        } elseif ($this->status == Status::EXCHANGE_REFUND) {
            $html = '<span><span class="badge badge--warning">' . trans('Refunded') . '</span>';
            if ($showTime) $html .= '<br>' . diffForHumans($this->updated_at);
            $html .= '</span>';
        } elseif ($this->status == Status::EXCHANGE_INITIAL) {
            $html = '<span><span class="badge badge--primary">' . trans('Initiated') . '</span>';
            if ($showTime) $html .= '<br>' . diffForHumans($this->updated_at);
            $html .= '</span>';
        }
        return $html;
    }
}
