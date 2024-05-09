<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'document_detail_id';
    protected $fillable = [
        'document_id',
        'content',
        'is_shared',
    ];

    public function docTitle(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'document_id');
    }
}
