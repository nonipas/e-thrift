<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'amount',
        'no_of_months',
        'previous_months_no',
        'balance',
        'previous_balance',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
