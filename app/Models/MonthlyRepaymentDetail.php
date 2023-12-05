<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRepaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_repayment_id',
        'member_id',
        'loan_id',
        'amount',
        'month',
        'year',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    public function monthlyRepayment()
    {
        return $this->belongsTo(MonthlyRepayment::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
