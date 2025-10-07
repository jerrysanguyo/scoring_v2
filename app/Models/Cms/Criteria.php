<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criterias';    
    protected $fillable = [
        'name',
        'no_of_participants',
        'remarks'
    ];
    protected $casts = [
        'locked_at' => 'datetime',
    ];

    public static function getAllCriterias()
    {
        return self::all();
    }

    public function details()
    {
        return $this->hasMany(CriteriaDetail::class, 'criteria_id');
    }

    public function getIsLockedAttribute(): bool
    {
        return !is_null($this->locked_at);
    }
}
