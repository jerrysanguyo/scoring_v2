<?php

namespace App\Http\Controllers;

use App\Models\Cms\Criteria;
use App\Models\Cms\CriteriaDetail;
use App\Models\Participant;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $criterias = Criteria::with(['details' => fn($q) => $q->orderBy('id')])->get();
        
    //     $participants = Participant::orderBy('id')->get();
        
    //     $userId = auth()->id();
    //     $myRows = Score::where('scored_by', $userId)->get(['participant_id','criteria_id','score']);
    //     $scoreMap = [];
    //     foreach ($myRows as $r) {
    //         $scoreMap[$r->participant_id][$r->criteria_id] = $r->score;
    //     }
        
    //     $byId = $criterias->keyBy('id');
        
    //     $stage1Id = optional($criterias->get(0))->id;
    //     $stage2Id = optional($criterias->get(1))->id;
    //     $stage3Id = optional($criterias->get(2))->id;
        
    //     $topSets = [];
    //     if ($stage1Id) {
    //         $topSets[$stage1Id] = $this->computeTopSet($stage1Id, 0, null);
    //     }
        
    //     if ($stage2Id && !empty($topSets[$stage1Id])) {
    //         $limit2 = 15; 
    //         $withinIds = $topSets[$stage1Id]->pluck('participant_id')->all();
    //         $topSets[$stage2Id] = $this->computeTopSet($stage1Id, $limit2, $withinIds); 
    //     }
        
    //     if ($stage3Id && !empty($topSets[$stage2Id])) {
    //         $limit3 = 6;
    //         $withinIds = $topSets[$stage2Id]->pluck('participant_id')->all();
    //         $topSets[$stage3Id] = $this->computeTopSet($stage2Id, $limit3, $withinIds); 
    //     }
        
    //     foreach ($criterias as $c) {
    //         if (!isset($topSets[$c->id]) && (int)($c->no_of_participants ?? 0) > 0) {
    //             $topSets[$c->id] = $this->computeTopSet($c->id, (int)$c->no_of_participants, null);
    //         }
    //     }
        
    //     $orderMapByCriteria = [];
    //     $overallTotalByCriteria = [];

    //     foreach ($topSets as $cid => $rows) {
    //         $rank = 0;
    //         $orderMapByCriteria[$cid] = [];
    //         $overallTotalByCriteria[$cid] = [];
            
    //         foreach ($rows as $row) {
    //             $orderMapByCriteria[$cid][$row->participant_id] = $rank++;
    //             $overallTotalByCriteria[$cid][$row->participant_id] = (float) $row->total;
    //         }
            
    //         if ($cid === $stage1Id) {
    //             foreach ($participants as $p) {
    //                 if (!array_key_exists($p->id, $orderMapByCriteria[$cid])) {
    //                     $orderMapByCriteria[$cid][$p->id] = $rank++;
    //                     $overallTotalByCriteria[$cid][$p->id] = 0.0;
    //                 }
    //             }
    //         }
    //     }
        
    //     $criteriaConfig = [];
    //     foreach ($criterias as $c) {
    //         $criteriaConfig[$c->id] = [
    //             'name'    => $c->name,
    //             'details' => $c->details->values()->map(function ($d, $i) {
    //                 return [
    //                     'id'         => $d->id,
    //                     'idx'        => $i + 1,
    //                     'name'       => $d->criteria_name,
    //                     'percentage' => (float)$d->percentage,
    //                 ];
    //             }),
    //         ];
    //     }

    //     return view('dashboard.index', [
    //         'criterias'               => $criterias,
    //         'participants'            => $participants,
    //         'scoreMap'                => $scoreMap,                
    //         'criteriaConfig'          => $criteriaConfig,      
    //         'orderMapByCriteria'      => $orderMapByCriteria,      
    //         'overallTotalByCriteria'  => $overallTotalByCriteria, 
    //     ]);
    // }

    
    public function index()
    {
        $user    = auth()->user();
        $userId  = $user->id;
        $isSA    = $user->hasRole('superadmin');

        $criterias    = Criteria::with(['details' => fn($q) => $q->orderBy('id')])->get();
        $participants = Participant::orderBy('id')->get();
        
        $myRows = Score::where('scored_by', $userId)->get(['participant_id','criteria_id','score']);
        $scoreMap = [];
        foreach ($myRows as $r) {
            $scoreMap[$r->participant_id][$r->criteria_id] = (float)$r->score;
        }
        
        $avgScoreMap = []; 
        if ($isSA) {
            $avgRows = Score::selectRaw('participant_id, criteria_id AS criteria_detail_id, AVG(score) AS avg_score')
                ->groupBy('participant_id','criteria_id')
                ->get();

            foreach ($avgRows as $r) {
                $avgScoreMap[$r->participant_id][$r->criteria_detail_id] = (float)$r->avg_score;
            }
        }
        
        $stage1Id = optional($criterias->get(0))->id;
        $stage2Id = optional($criterias->get(1))->id;
        $stage3Id = optional($criterias->get(2))->id;
        
        $computeTopSetScoped = function (int $criteriaId, int $limit = 0, ?array $withinIds = null) use ($isSA) {
            $details = CriteriaDetail::where('criteria_id', $criteriaId)
                ->select(['id','criteria_id','percentage'])
                ->get();

            if ($details->isEmpty()) {
                return collect();
            }

            $detailIds = $details->pluck('id')->all();

            $query = Score::query()->whereIn('criteria_id', $detailIds);
            if (!$isSA) {
                $query->where('scored_by', auth()->id());
            }
            
            $rows = $query
                ->selectRaw('participant_id, criteria_id AS criteria_detail_id, ' . ($isSA ? 'AVG(score)' : 'MAX(score)') . ' AS val')
                ->groupBy('participant_id','criteria_id')
                ->get();

            if ($withinIds) {
                $rows = $rows->whereIn('participant_id', $withinIds);
            }
            
            $byParticipant = [];
            foreach ($rows as $r) {
                $pid = (int)$r->participant_id;
                $byParticipant[$pid] = ($byParticipant[$pid] ?? 0) + (float)$r->val;
            }
            
            if ($withinIds) {
                foreach ($withinIds as $pid) {
                    $pid = (int)$pid;
                    if (!array_key_exists($pid, $byParticipant)) {
                        $byParticipant[$pid] = 0.0;
                    }
                }
            }

            $coll = collect($byParticipant)
                ->map(fn($total, $pid) => (object)['participant_id' => (int)$pid, 'total' => (float)$total])
                ->sortByDesc('total')
                ->values();

            if ($limit > 0) {
                $coll = $coll->take($limit)->values();
            }

            return $coll;
        };
        
        $topSets = [];
        if ($stage1Id) {
            $limit1 = (int) ($criterias->firstWhere('id', $stage1Id)?->no_of_participants ?? 0);
            $topSets[$stage1Id] = $computeTopSetScoped($stage1Id, $limit1 > 0 ? $limit1 : 0, null);
        }
        
        if ($stage2Id && !empty($topSets[$stage1Id])) {
            $limit2 = (int) ($criterias->firstWhere('id', $stage2Id)?->no_of_participants ?? 0);
            $withinIds = $topSets[$stage1Id]
                ->take($limit2 > 0 ? $limit2 : $topSets[$stage1Id]->count())
                ->pluck('participant_id')
                ->all();
            $topSets[$stage2Id] = $computeTopSetScoped($stage2Id, $limit2 > 0 ? $limit2 : 0, $withinIds);
        }
        
        if ($stage3Id && !empty($topSets[$stage2Id])) {
            $limit3 = (int) ($criterias->firstWhere('id', $stage3Id)?->no_of_participants ?? 0);
            $withinIds = $topSets[$stage2Id]
                ->take($limit3 > 0 ? $limit3 : $topSets[$stage2Id]->count())
                ->pluck('participant_id')
                ->all();
            $topSets[$stage3Id] = $computeTopSetScoped($stage3Id, $limit3 > 0 ? $limit3 : 0, $withinIds);
        }
                
        foreach ($criterias as $c) {
            if (!isset($topSets[$c->id]) && (int)($c->no_of_participants ?? 0) > 0) {
                $topSets[$c->id] = $computeTopSetScoped($c->id, (int)$c->no_of_participants, null);
            }
        }
        
        $orderMapByCriteria = [];
        $overallTotalByCriteria = [];
        foreach ($topSets as $cid => $rows) {
            $rank = 0;
            $orderMapByCriteria[$cid] = [];
            $overallTotalByCriteria[$cid] = [];
            foreach ($rows as $row) {
                $orderMapByCriteria[$cid][$row->participant_id] = $rank++;
                $overallTotalByCriteria[$cid][$row->participant_id] = (float) $row->total;
            }
            
            if ($cid === $stage1Id) {
                foreach ($participants as $p) {
                    if (!array_key_exists($p->id, $orderMapByCriteria[$cid])) {
                        $orderMapByCriteria[$cid][$p->id] = $rank++;
                        $overallTotalByCriteria[$cid][$p->id] = 0.0;
                    }
                }
            }
        }
        
        $criteriaConfig = [];
        foreach ($criterias as $c) {
            $criteriaConfig[$c->id] = [
                'name'    => $c->name,
                'details' => $c->details->values()->map(function ($d, $i) {
                    return [
                        'id'         => $d->id,
                        'idx'        => $i + 1,
                        'name'       => $d->criteria_name,
                        'percentage' => (float)$d->percentage,
                    ];
                }),
            ];
        }

        $scorersMap = [];

        if ($isSA) {
            $scoredRows = Score::query()
                ->select(['participant_id', 'criteria_id', 'scored_by'])
                ->with(['user:id,first_name,last_name,email']) 
                ->distinct()
                ->get();

            foreach ($scoredRows as $row) {
                $pid = (int) $row->participant_id;
                $cd  = (int) $row->criteria_id; 
                $u   = $row->user;

                $name = $u
                    ? trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ($u->email ?? ('Judge #' . $row->scored_by))
                    : ('Judge #' . $row->scored_by);

                $scorersMap[$pid][$cd][] = $name;
            }
        }

        return view('dashboard.index', [
            'criterias'               => $criterias,
            'participants'            => $participants,
            'scoreMap'                => $scoreMap,
            'avgScoreMap'             => $avgScoreMap,
            'canSeeAverages'          => $isSA,
            'criteriaConfig'          => $criteriaConfig,
            'orderMapByCriteria'      => $orderMapByCriteria,
            'overallTotalByCriteria'  => $overallTotalByCriteria,
            'scorersMap'              => $scorersMap,
        ]);
    }


    public function store(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'participant_id'     => ['required','integer','exists:participants,id'],
            'criteria_parent_id' => ['required','integer','exists:criterias,id'],
            'scores'             => ['required','array'],
            'scores.*.DETAIL'    => ['required','array'],
        ]);

        $participantId    = (int) $validated['participant_id'];
        $criteriaParentId = (int) $validated['criteria_parent_id'];

        $parent = Criteria::findOrFail($criteriaParentId);
        
        if ($parent->locked_at) {
            return response()->json(['message' => 'This criteria is locked for editing.'], 423);
        }
        
        $detailScores   = data_get($validated, "scores.$criteriaParentId.DETAIL", []);
        $validDetailIds = CriteriaDetail::where('criteria_id', $criteriaParentId)->pluck('id')->all();

        foreach ($detailScores as $detailId => $raw) {
            if (!in_array((int)$detailId, $validDetailIds, true)) {
                return response()->json(['message' => 'Invalid criteria detail.'], 422);
            }

            Score::updateOrCreate(
                [
                    'participant_id' => $participantId,
                    'criteria_id'    => (int) $detailId,
                    'scored_by'      => $userId,
                ],
                [
                    'score' => round((float)$raw, 2),
                ]
            );
        }

        return response()->json(['message' => 'Scores saved.']);
    }
    
    public function showForCriteria(Participant $participant, Criteria $criteria)
    {
        $userId = auth()->id();

        $details = $criteria->details()->select('id','criteria_name','percentage')->get();
        $scores  = Score::ownedBy($userId)
            ->where('participant_id', $participant->id)
            ->whereIn('criteria_id', $details->pluck('id'))
            ->pluck('score', 'criteria_id');

        return response()->json(['details' => $details, 'scores' => $scores]);
    }

    private function computeTopSet(int $criteriaId, int $limit = 0, ?array $withinIds = null)
    {
        $detailIds = CriteriaDetail::where('criteria_id', $criteriaId)->pluck('id');
        
        $avgPerDetail = DB::table('scores')
            ->select('participant_id', 'criteria_id', DB::raw('AVG(score) as avg_score'))
            ->whereIn('criteria_id', $detailIds)
            ->groupBy('participant_id', 'criteria_id');
            
        $totals = DB::query()
            ->fromSub($avgPerDetail, 'x')
            ->select('participant_id', DB::raw('SUM(avg_score) as total'))
            ->when(!empty($withinIds), fn($q) => $q->whereIn('participant_id', $withinIds))
            ->groupBy('participant_id')
            ->orderByDesc('total');

        if ($limit > 0) {
            $totals->limit($limit);
        }
        
        return collect($totals->get());
    }

    

    private function orderParticipantsBy(array $idsWithRank, $participants)
    {
        // $idsWithRank = [participant_id => rankIndex]
        return $participants
            ->filter(fn($p) => array_key_exists($p->id, $idsWithRank))
            ->sortBy(fn($p) => $idsWithRank[$p->id])
            ->values();
    }
}