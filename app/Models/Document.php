<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'document_id';
    protected $keyType = 'string';
    protected $fillable = [
        'document_id',
        'title',
        'password',
        'user_id'
    ];
}
