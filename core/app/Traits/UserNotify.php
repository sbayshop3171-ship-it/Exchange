<?php

namespace App\Traits;

use App\Constants\Status;

trait UserNotify
{
    public static function notifyToUser()
    {
        return [
            'allUsers'               => 'All Users',
            'selectedUsers'          => 'Selected Users',
            'kycUnverified'          => 'Kyc Unverified Users',
            'kycVerified'            => 'Kyc Verified Users',
            'kycPending'             => 'Kyc Pending Users',
            'withBalance'            => 'With Balance Users',
            'emptyBalanceUsers'      => 'Empty Balance Users',
            'twoFaDisableUsers'      => '2FA Disable User',
            'twoFaEnableUsers'       => '2FA Enable User',
            'hasWithdrawUsers'       => 'Withdraw Users',
            'pendingWithdrawUsers'   => 'Pending Withdraw Users',
            'rejectedWithdrawUsers'  => 'Rejected Withdraw Users',
            'pendingTicketUser'      => 'Pending Ticket Users',
            'answerTicketUser'       => 'Answer Ticket Users',
            'closedTicketUser'       => 'Closed Ticket Users',
            'notLoginUsers'          => 'Last Few Days Not Login Users',
            'exchangeInitiatedUser'  => 'Initiated Exchange Users',
            'exchangePendingUser'    => 'Pending Exchange Users',
            'exchangeApprovedUser'   => 'Approved Exchange Users',
            'exchangeRejectedUser'   => 'Rejected Exchange Users',
        ];
    }

    public function scopeExchangeInitiatedUser($query)
    {
        $query->whereHas('exchanges', function ($exchanges) {
            $exchanges->initiated();
        });
    }
    public function scopeExchangePendingUser($query)
    {
        $query->whereHas('exchanges', function ($exchanges) {
            $exchanges->pending();
        });
    }
    public function scopeExchangeApprovedUser($query)
    {
        $query->whereHas('exchanges', function ($exchanges) {
            $exchanges->approved();
        });
    }
    public function scopeExchangeRejectedUser($query)
    {
        $query->whereHas('exchanges', function ($exchanges) {
            $exchanges->canceled();
        });
    }

    public function scopeSelectedUsers($query)
    {
        return $query->whereIn('id', request()->user ?? []);
    }

    public function scopeAllUsers($query)
    {
        return $query;
    }

    public function scopeEmptyBalanceUsers($query)
    {
        return $query->where('balance', '<=', 0);
    }

    public function scopeTwoFaDisableUsers($query)
    {
        return $query->where('ts', Status::DISABLE);
    }

    public function scopeTwoFaEnableUsers($query)
    {
        return $query->where('ts', Status::ENABLE);
    }

    public function scopeHasWithdrawUsers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->approved();
        });
    }

    public function scopePendingWithdrawUsers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->pending();
        });
    }

    public function scopeRejectedWithdrawUsers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->rejected();
        });
    }

    public function scopePendingTicketUser($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY]);
        });
    }

    public function scopeClosedTicketUser($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->where('status', Status::TICKET_CLOSE);
        });
    }

    public function scopeAnswerTicketUser($query)
    {
        return $query->whereHas('tickets', function ($q) {

            $q->where('status', Status::TICKET_ANSWER);
        });
    }

    public function scopeNotLoginUsers($query)
    {
        return $query->whereDoesntHave('loginLogs', function ($q) {
            $q->whereDate('created_at', '>=', now()->subDays(request()->number_of_days ?? 10));
        });
    }

    public function scopeKycVerified($query)
    {
        return $query->where('kv', Status::KYC_VERIFIED);
    }
}
