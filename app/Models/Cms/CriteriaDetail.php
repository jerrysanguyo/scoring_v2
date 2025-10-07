<?php

namespace App\Models\Cms;

use App\Models\Score;
use Illuminate\Database\Eloquent\Model;

class CriteriaDetail extends Model
{
    protected $table = 'criteria_details';
    protected $fillable = [
        'criteria_id', 
        'criteria_name', 
        'percentage'
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }

    public function score()
    {
        return $this->hasMany(Score::class, 'criteria_id');
    }
}
