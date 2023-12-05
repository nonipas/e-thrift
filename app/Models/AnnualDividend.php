<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualDividend extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'total_dividend',
        'year',
        'status',
    ];

    public function annualDividendDetails()
    {
        return $this->hasMany(AnnualDividendDetail::class);
    }
}
