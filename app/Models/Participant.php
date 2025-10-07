<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participants';
    protected $fillable = [
        'name'
    ];
    
    public static function getAllParticipants()
    {
        return static::query()->select('id', 'name')->latest()->get();
    }

    public function score()
    {
        return $this->hasMany(Score::class, 'participant_id');
    }
}
