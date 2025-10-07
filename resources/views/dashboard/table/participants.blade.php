<div class="table-responsive">
    <table class="table table-hover align-middle gs-0 gy-3 table-rounded kt-table">
        <thead>
            <tr class="bg-light kt-thead">
                <th class="ps-4 w-50px">#</th>
                <th class="text-gray-700 text-uppercase fw-bold fs-7">Participant</th>

                @foreach($criteria->details as $detail)
                <th class="text-center text-gray-700 text-uppercase fw-bold fs-7">
                    {{ $detail->criteria_name }}
                    <div class="d-block text-muted small">{{ $detail->percentage }}%</div>
                </th>
                @endforeach
                <th class="text-center text-gray-700 text-uppercase fw-bold fs-7">Weighted Score</th>

                <th class="text-end text-gray-700 text-uppercase fw-bold fs-7 pe-3">Action</th>
            </tr>
        </thead>

        @php
        $orderMap = $orderMapByCriteria[$criteria->id] ?? [];
        $eligibleIds = array_keys($orderMap);

        $participantsForTab = $participants
        ->filter(fn($p) => in_array($p->id, $eligibleIds))
        ->sortBy(fn($p) => $orderMap[$p->id] ?? PHP_INT_MAX)
        ->values();
        @endphp

        <tbody>
            @forelse($participantsForTab as $p)
            @php
            $participantScores = $scoreMap[$p->id] ?? [];
            $weightedTotal = 0;
            @endphp

            <tr class="kt-row">
                <td class="ps-4">
                    <div class="symbol symbol-25px symbol-circle bg-light-primary">
                        <span class="symbol-label fw-bold text-primary">{{ $loop->iteration }}</span>
                    </div>
                </td>

                <td class="fw-semibold text-gray-800">{{ $p->name }}</td>

                @foreach($criteria->details as $detail)
                @php
                $val = $participantScores[$detail->id] ?? null;
                if (!is_null($val)) {
                $weightedTotal += (float) $val;
                }
                @endphp
                <td class="text-center">
                    @if(!is_null($val))
                    <span class="badge bg-info text-dark">{{ number_format($val, 2) }}</span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>
                @endforeach

                <td class="text-center fw-bold">
                    @if($weightedTotal > 0)
                    <span class="badge bg-primary text-white">{{ number_format($weightedTotal, 2) }}</span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>

                <td class="pe-3 text-end">
                    <button type="button" class="btn btn-sm btn-primary js-open-score"
                        data-participant-id="{{ $p->id }}" data-participant-name="{{ $p->name }}"
                        {{ $isLocked ? 'disabled' : '' }}>
                        <i class="ki-duotone ki-pencil fs-5 me-1"></i> Score
                    </button>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="{{ 4 + $criteria->details->count() }}" class="text-center text-muted py-6">
                    No participants for this stage.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>