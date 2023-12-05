<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'month',
        'year',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    public function monthlyRepaymentDetails()
    {
        return $this->hasMany(MonthlyRepaymentDetail::class);
    }
}
