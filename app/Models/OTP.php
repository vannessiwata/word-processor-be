<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    protected $table = 'one_time_password';
    protected $primaryKey = 'otp_id';
    protected $keyType = 'string';

    protected $fillable = [
        'otp',
        'otp_id',
        'user_id',
        'type',
        'document_id',
        'expired_at',
    ];
}
