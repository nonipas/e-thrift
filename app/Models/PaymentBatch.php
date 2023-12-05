<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_amount',
        'size',
        'status',
        'is_approved',
        'approved_by',
        'approved_at',

    ];
}
