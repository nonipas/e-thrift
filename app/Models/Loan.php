<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'beneficiary_type',
        'beneficiary_name',
        'beneficiary_account_no',
        'beneficiary_bank',
        'amount',
        'interest',
        'duration',
        'monthly_repayment',
        'total_repayment',
        'balance',
        'repayment_start_month',
        'repayment_status',
        'type',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
