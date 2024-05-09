<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharedDocument extends Model
{
    use HasFactory;
    protected $table = 'document_shared';
    protected $primaryKey = 'document_shared_id';
    protected $keyType = 'string';
    protected $fillable = [
        'document_shared_id',
        'document_id',
        'user_id',
    ];

    public function documentData() : BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }
}
