<?php

namespace App\Services;

use App\Models\Cms\Criteria;
use Illuminate\Support\Facades\DB;

class CriteriaServices
{
    public function criteriaStore(array $payload): Criteria
    {
        return DB::transaction(function () use ($payload) {
            $criteria = Criteria::create([
                'name'               => $payload['name'],
                'no_of_participants' => $payload['no_of_participants'],
                'remarks'            => $payload['remarks'] ?? null,
            ]);

            if (! empty($payload['details'])) {
                $criteria->details()->insert(
                    collect($payload['details'])->map(function ($row) use ($criteria) {
                        return [
                            'criteria_id'   => $criteria->id,
                            'criteria_name' => $row['criteria_name'],
                            'percentage'    => (int) $row['percentage'],
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ];
                    })->all()
                );
            }

            return $criteria->load('details');
        });
    }

    public function criteriaUpdate(Criteria $criteria, array $payload): Criteria 
    { 
        return DB::transaction(function () use ($criteria, $payload) { 
            $criteria->update([ 
                'name' => $payload['name'], 
                'no_of_participants' => $payload['no_of_participants'], 
                'remarks' => $payload['remarks'] ?? null, 
            ]); 
            
            $criteria->details()->delete(); 
            
            if (! empty($payload['details'])) { 
                $criteria->details()->insert( 
                    collect($payload['details'])->map(function ($row) use ($criteria) { 
                        return [ 
                            'criteria_id' => $criteria->id, 
                            'criteria_name' => $row['criteria_name'], 
                            'percentage' => (int) $row['percentage'], 
                            'created_at' => now(), 
                            'updated_at' => now(), 
                        ]; 
                    })->all() 
                ); 
            } 
            return $criteria->load('details'); 
        }); 
    }
    
    public function criteriaDestroy(Criteria $criteria): Criteria
    {
        $criteria->loadMissing('details');

        $criteria->delete();

        return $criteria;
    }
}