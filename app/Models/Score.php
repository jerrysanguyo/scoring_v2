<?php

namespace App\Models;

use App\Models\Cms\CriteriaDetail;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';
    protected $fillable = [
        'participant_id',
        'criteria_id',
        'score',
        'scored_by'
    ];

    public function scopeOwnedBy($q, int $userId) {
        return $q->where('scored_by', $userId);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function criteria()
    {
        return $this->belongsTo(CriteriaDetail::class, 'criteria_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'scored_by');
    }
}
