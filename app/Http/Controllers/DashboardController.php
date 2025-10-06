<?php

namespace App\Http\Controllers;

use App\Models\Cms\Criteria;
use App\Models\Participant;

class DashboardController extends Controller
{
    public function index()
    {
        $criterias = Criteria::with(['details' => fn($q) => $q->orderBy('id')])
            ->orderBy('id')
            ->get();

        $participants = Participant::select('id','name')->orderBy('id')->get();

        $criteriaConfig = [];
        foreach ($criterias as $c) {
            $details = [];
            foreach ($c->details->values() as $i => $d) {
                $details[] = [
                    'id'         => $d->id,
                    'idx'        => $i + 1,
                    'name'       => $d->criteria_name,
                    'percentage' => (float) $d->percentage,
                ];
            }
            $criteriaConfig[$c->id] = [
                'id'      => $c->id,
                'name'    => $c->name,
                'details' => $details,
            ];
        }

        return view('dashboard.index', compact(
            'criterias',
            'participants',
            'criteriaConfig'
        ));
    }
}
