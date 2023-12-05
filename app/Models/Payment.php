<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'batch_id',
        'payment_type',
        'bank',
        'beneficiary_name',
        'beneficiary_account_no',
        'amount',
        'status',
        'is_approved',
        'approved_by',
        'approved_at',
        'is_processed',
        'processed_by',
        'processed_at',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

}
