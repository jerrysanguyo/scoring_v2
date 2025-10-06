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

    public static function getAllCriterias()
    {
        return self::all();
    }

    public function details()
    {
        return $this->hasMany(CriteriaDetail::class, 'criteria_id');
    }
}
