<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualDividendDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'annual_dividend_id',
        'member_id',
        'year',
        'amount',
        'status',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    public function annualDividend()
    {
        return $this->belongsTo(AnnualDividend::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
