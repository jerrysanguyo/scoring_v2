<div class="table-responsive">
    <div class="d-flex align-items-center justify-content-between pb-2">
        @if(!empty($canSeeAverages))
        <div class="badge bg-secondary px-3 py-2">
            <i class="ki-duotone ki-chart-line fs-6 me-1"></i>
            Superadmin View · Averages across all judges
        </div>
        @else
        <div class="badge bg-light-primary text-primary px-3 py-2">
            <i class="ki-duotone ki-user fs-6 me-1"></i>
            Your scores only
        </div>
        @endif
    </div>

    <table class="table table-hover align-middle gs-0 gy-3 table-rounded kt-table">
        <thead>
            <tr class="bg-light kt-thead">
                <th class="ps-4 w-50px">#</th>
                <th class="text-gray-700 text-uppercase fw-bold fs-7">Participant</th>

                @foreach($criteria->details as $detail)
                <th class="text-center text-gray-700 text-uppercase fw-bold fs-7">
                    {{ $detail->criteria_name }}
                    <div class="d-block text-muted small">
                        {{ $detail->percentage }}%
                        @if(!empty($canSeeAverages))
                        · AVG
                        @endif
                    </div>
                </th>
                @endforeach

                <th class="text-center text-gray-700 text-uppercase fw-bold fs-7">
                    Total Score
                    <div class="d-block text-muted small">
                        @if(!empty($canSeeAverages)) AVG Total @else Your Total @endif
                    </div>
                </th>

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
            <tr class="kt-row">
                <td class="ps-4">
                    <div class="symbol symbol-25px symbol-circle bg-light-primary">
                        <span class="symbol-label fw-bold text-primary">{{ $loop->iteration }}</span>
                    </div>
                </td>

                <td class="fw-semibold text-gray-800">{{ $p->name }}</td>

                @foreach($criteria->details as $detail)
                @php
                $cellVal = !empty($canSeeAverages)
                ? ($avgScoreMap[$p->id][$detail->id] ?? null)
                : (($scoreMap[$p->id][$detail->id] ?? null));
                
                $judges = !empty($canSeeAverages) ? ($scorersMap[$p->id][$detail->id] ?? []) : [];
                $tooltipText = count($judges)
                ? 'Scored by: ' . implode(', ', $judges)
                : 'No judges have scored yet.';
                @endphp

                <td class="text-center">
                    @if(!is_null($cellVal))
                    <span class="badge {{ !empty($canSeeAverages) ? 'bg-secondary' : 'bg-info' }} text-white">
                        {{ number_format($cellVal, 2) }}
                    </span>
                    
                    @if(!empty($canSeeAverages))
                    <i class="ki-duotone ki-information-5 fs-6 text-gray-500 ms-1 align-middle" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="{{ $tooltipText }}">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    @endif
                    @else
                    <span class="text-muted">—</span>

                    @if(!empty($canSeeAverages))
                    <i class="ki-duotone ki-information-5 fs-6 text-gray-400 ms-1 align-middle" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="No judges have scored yet.">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    @endif
                    @endif
                </td>
                @endforeach

                @php
                $finalTotal = $overallTotalByCriteria[$criteria->id][$p->id] ?? null;
                @endphp
                <td class="text-center fw-bold">
                    @if(!is_null($finalTotal) && $finalTotal > 0)
                    <span class="badge {{ !empty($canSeeAverages) ? 'bg-dark' : 'bg-primary' }} text-white">
                        {{ number_format($finalTotal, 2) }}
                    </span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                </td>

                <td class="pe-3 text-end">
                    @role('admin|user')
                    <button type="button" class="btn btn-sm btn-primary js-open-score"
                        data-participant-id="{{ $p->id }}" data-participant-name="{{ $p->name }}"
                        {{ $isLocked ? 'disabled' : '' }}>
                        <i class="ki-duotone ki-pencil fs-5 me-1"></i> Score
                    </button>
                    @endrole
                    @role('superadmin')
                    <button type="button" class="btn btn-sm btn-light" disabled>
                        <i class="ki-duotone ki-eye fs-5 me-1"></i> View Only
                    </button>
                    @endrole
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const initTips = (root = document) => {
        [].slice.call(root.querySelectorAll('[data-bs-toggle="tooltip"]'))
            .forEach(el => new bootstrap.Tooltip(el));
    };
    initTips();
    
    document.querySelectorAll('#criteriaTabs [data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', () => initTips(document.getElementById(
            'criteriaTabContent')));
    });
});
</script>
@endpush