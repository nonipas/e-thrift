<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyContributionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_contribution_id',
        'member_id',
        'amount',
        'month',
        'year',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    public function monthlyContribution()
    {
        return $this->belongsTo(MonthlyContribution::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
