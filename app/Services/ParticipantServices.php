<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Support\Facades\Auth;

class ParticipantServices
{
    public function participantStore(array $data): Participant
    {
        $participant = Participant::create($data);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($participant)
            ->withProperties(['attributes' => $participant->toArray()])
            ->event('created')
            ->log('participant_created');

        return $participant;
    }

    public function participantUpdate(Participant $participant, array $data): Participant
    {
        $original = $participant->getOriginal();

        $participant->update($data);
        $participant->refresh();

        $changes = [
            'before' => $original,
            'after'  => $participant->getAttributes(),
            'diff'   => array_keys(array_diff_assoc($participant->getAttributes(), $original)),
        ];

        activity()
            ->causedBy(Auth::user())
            ->performedOn($participant)
            ->withProperties($changes)
            ->event('updated')
            ->log('participant_updated');

        return $participant;
    }

    public function participantDestroy(Participant $participant): void
    {
        $snapshot = $participant->toArray();

        $participant->delete();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($participant)
            ->withProperties(['deleted' => $snapshot])
            ->event('deleted')
            ->log('participant_deleted');
    }
}