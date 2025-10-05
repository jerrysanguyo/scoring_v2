<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criterias';
    protected $fillable = [
        'title',
        'no_of_participants',
        'remarks'
    ];

    public static function getAllCriterias()
    {
        return self::all();
    }
}
