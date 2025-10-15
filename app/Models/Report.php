<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'reporter_user_id',
        'reason',
        'description',
        'status',
        'admin_note',
        'resolved_at',
        'resolved_by',
    ];

    /**
     * Relations
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    /**
     * Statuts possibles
     */
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Cast des attributs
     */
    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Relation avec l'admin qui a traité le signalement
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}