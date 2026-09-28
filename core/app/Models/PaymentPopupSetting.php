<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PaymentPopupSetting extends Model { protected $fillable=['unlock_fee_amount','fee_currency','is_trx_required','is_proof_required','vat_enabled','vat_rate','fixed_fee_enabled','fixed_fee_amount']; protected $casts=['unlock_fee_amount'=>'decimal:8','is_trx_required'=>'boolean','is_proof_required'=>'boolean','vat_enabled'=>'boolean','vat_rate'=>'decimal:3','fixed_fee_enabled'=>'boolean','fixed_fee_amount'=>'decimal:8']; }
