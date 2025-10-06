<?php

namespace App\Http\Controllers\Cms;

use App\Http\Requests\CriteriaRequest;
use App\Models\Cms\Criteria;
use App\DataTables\CmsDataTable;
use App\Http\Controllers\Controller;
use App\Services\CriteriaServices;
use Illuminate\Support\Facades\Auth;

class CriteriaController extends Controller
{
    protected CriteriaServices $criteriaService;

    public function __construct(CriteriaServices $criteriaService)
    {
        $this->criteriaService = $criteriaService;
    }

    public function index(CmsDataTable $dataTable)
    {
        $page_title = 'Criteria';
        $resource   = 'criteria';
        $columns    = ['id', 'title', '# of participants', 'criterias', 'Action'];
        $data       = Criteria::getAllCriterias();

        return $dataTable->render('criteria.index', compact(
            'page_title',
            'columns',
            'data',
            'resource',
            'dataTable'
        ));
    }

    public function store(CriteriaRequest $request)
    {
        $criteria = $this->criteriaService->criteriaStore($request->validatedPayload());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($criteria)
            ->withProperties(['name' => $criteria->name])
            ->log('Criteria created by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name);

        return redirect()
            ->route(Auth::user()->getRoleNames()->first() . '.criteria.index')
            ->with('success', 'Criteria created successfully.');
    }

    public function update(CriteriaRequest $request, Criteria $criteria)
    {
        $criteria = $this->criteriaService->criteriaUpdate($criteria, $request->validatedPayload());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($criteria)
            ->withProperties(['name' => $criteria->name])
            ->log('Criteria updated by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name);

        return redirect()
            ->route(Auth::user()->getRoleNames()->first() . '.criteria.index')
            ->with('success', 'Criteria updated successfully.');
    }

    public function destroy(Criteria $criteria)
    {
        $deleted = $this->criteriaService->criteriaDestroy($criteria);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($deleted)
            ->withProperties(['name' => $deleted->name])
            ->log('Criteria deleted by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name);

        return redirect()
            ->route(Auth::user()->getRoleNames()->first() . '.criteria.index')
            ->with('success', 'Criteria deleted successfully.');
    }

    public function showJson(Criteria $criteria)
    {
        $criteria->load('details');

        return response()->json([
            'id'                 => $criteria->id,
            'name'               => $criteria->name,
            'no_of_participants' => $criteria->no_of_participants,
            'remarks'            => $criteria->remarks,
            'details'            => $criteria->details->map(fn($d) => [
                'criteria_name' => $d->criteria_name,
                'percentage'    => (float) $d->percentage,
            ])->values(),
            'update_url'         => route(auth()->user()->getRoleNames()->first().'.criteria.update', $criteria),
        ]);
    }
}