<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'payment_batch_id',
        'payment_type',
        'bank',
        'beneficiary_name',
        'beneficiary_account_no',
        'amount',
        'description',
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
        return $this->belongsTo(PaymentBatch::class, 'payment_batch_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_type', 'slug');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank', 'code');
    }

}
