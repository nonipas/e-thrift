<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'employment_id',
        'email',
        'phone',
        'bank',
        'account_number',
        'department',
        'status',
    ];
    
}
