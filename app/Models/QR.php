<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QR extends Model
{
    use HasFactory;
    protected $table = "qrcodes";
    protected $fillable = [
        'title',
        'subtitle',
        'user_id',
        'content',
        'type',
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
