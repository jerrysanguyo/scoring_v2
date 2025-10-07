<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Services\ParticipantServices;
use App\Http\Requests\ParticipantRequest;
use App\DataTables\CmsDataTable;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function __construct(private ParticipantServices $service) {}

    public function index(CmsDataTable $dataTable)
    {
        $page_title = 'Participants';
        $resource   = 'participant';
        $columns    = ['id', 'name', 'Action'];
        $data       = Participant::getAllParticipants();

        return $dataTable->render('participant.index', compact(
            'page_title',
            'columns',
            'data',
            'resource',
            'dataTable'
        ));
    }

    public function store(ParticipantRequest $request)
    {
        $participant = $this->service->participantStore($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Participant created successfully.',
                'data'    => $participant,
            ], 201);
        }

        return back()->with('success', 'Participant created successfully.');
    }

    public function update(ParticipantRequest $request, Participant $participant)
    {
        $participant = $this->service->participantUpdate($participant, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Participant updated successfully.',
                'data'    => $participant,
            ]);
        }

        return back()->with('success', 'Participant updated successfully.');
    }

    public function destroy(Request $request, Participant $participant)
    {
        $this->service->participantDestroy($participant);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Participant deleted successfully.']);
        }

        return back()->with('success', 'Participant deleted successfully.');
    }
}