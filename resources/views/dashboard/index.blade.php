@extends('layouts.dashboard')

@section('content')
@include('components.alert')

<div class="card shadow-sm">
    <div class="card-header border-0 pt-6 pb-2">
        <h3 class="fw-bold mb-0">All Criterias</h3>
        <div class="text-muted">Each tab represents a criteria with its detailed breakdown and status.</div>
    </div>

    <div class="card-body">

        @if(isset($criterias) && $criterias->count())
        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" id="criteriaTabs" role="tablist">
            @foreach($criterias as $criteria)
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center gap-2 {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $criteria->id }}" data-bs-toggle="tab" data-bs-target="#criteria-{{ $criteria->id }}"
                    type="button" role="tab" aria-controls="criteria-{{ $criteria->id }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <i class="ki-duotone ki-category fs-4"></i>
                    <span>{{ $criteria->name }}</span>
                </button>
            </li>
            @endforeach
        </ul>

        <div class="tab-content" id="criteriaTabContent">
            @foreach($criterias as $criteria)
            @php
            $totalPct = $criteria->details->sum('percentage');
            @endphp

            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="criteria-{{ $criteria->id }}"
                role="tabpanel" aria-labelledby="tab-{{ $criteria->id }}">

                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
                    <div class="mb-2 mb-md-0">
                        <div class="fw-bold fs-5 text-gray-800">{{ $criteria->name }}</div>
                        <div class="text-muted small">
                            Participants: <span class="fw-semibold">{{ $criteria->no_of_participants }}</span>
                            @if(!empty($criteria->remarks))
                            • Remarks: <span class="fw-normal">{{ $criteria->remarks }}</span>
                            @endif
                        </div>
                    </div>
                </div>

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

                                <th class="text-end text-gray-700 text-uppercase fw-bold fs-7 pe-3">Action</th>
                            </tr>
                        </thead>

                        @php
                        $limit = (int) ($criteria->no_of_participants ?? 0);
                        if (method_exists($participants, 'take')) {
                        $participantsLimited = $limit > 0 ? $participants->take($limit) : $participants;
                        $totalParticipants = method_exists($participants, 'count') ? $participants->count() :
                        (is_array($participants) ? count($participants) : 0);
                        $showingCount = method_exists($participantsLimited, 'count') ? $participantsLimited->count() :
                        (is_array($participantsLimited) ? count($participantsLimited) : 0);
                        } else {
                        $participantsLimited = $limit > 0 ? array_slice($participants, 0, $limit) : $participants;
                        $totalParticipants = is_array($participants) ? count($participants) : 0;
                        $showingCount = is_array($participantsLimited) ? count($participantsLimited) : 0;
                        }
                        @endphp
                        <tbody>
                            @forelse($participantsLimited as $p)
                            <tr class="kt-row">
                                <td class="ps-4">
                                    <div class="symbol symbol-25px symbol-circle bg-light-primary">
                                        <span class="symbol-label fw-bold text-primary">{{ $loop->iteration }}</span>
                                    </div>
                                </td>

                                <td class="fw-semibold text-gray-800">{{ $p->name }}</td>

                                @foreach($criteria->details as $detail)
                                <td class="text-center text-muted">—</td>
                                @endforeach

                                <td class="pe-3 text-end">
                                    <button type="button" class="btn btn-sm btn-primary js-open-score"
                                        data-participant-id="{{ $p->id }}" data-participant-name="{{ $p->name }}">
                                        <i class="ki-duotone ki-pencil fs-5 me-1"></i> Score
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ 3 + $criteria->details->count() }}" class="text-center text-muted py-6">
                                    No participants found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info">
            No criterias found.
        </div>
        @endif

    </div>
</div>
@endsection
@push('styles')
<style>
.nav-line-tabs .nav-link.active {
    color: #0d6efd !important;
    border-bottom: 2px solid #0d6efd !important;
    font-weight: 600;
}

.nav-line-tabs .nav-link {
    transition: all 0.2s ease;
}

.nav-line-tabs .nav-link:hover {
    color: #0d6efd !important;
}
</style>
@endpush
@push('modals')
@include('dashboard.modal.scoring')
@endpush
@push('scripts')
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
window.CRITERIA_DETAILS = @json($criteriaConfig);

(function() {
    function getActiveCriteriaId() {
        const activeBtn = document.querySelector('#criteriaTabs .nav-link.active');
        if (!activeBtn) return null;
        const target = activeBtn.getAttribute('data-bs-target');
        if (!target) return null;
        const m = target.match(/^#criteria-(\d+)$/);
        return m ? parseInt(m[1], 10) : null;
    }

    function renderScoreRows(criteriaId) {
        const cfg = window.CRITERIA_DETAILS?. [criteriaId]; // <-- NO space after ?.
        const tbody = document.getElementById('scoreRows');
        if (!tbody) return;
        tbody.innerHTML = '';
        if (!cfg) return;

        cfg.details.forEach(d => {
            const tr = document.createElement('tr');
            tr.className = 'kt-row';
            tr.innerHTML = `
          <td class="ps-4">
            <div class="symbol symbol-25px symbol-circle bg-light-primary">
              <span class="symbol-label fw-bold text-primary">${d.idx}</span>
            </div>
          </td>
          <td class="fw-semibold text-gray-800">${d.name}</td>
          <td class="text-center text-muted">${d.percentage}%</td>
          <td class="pe-4 text-end">
            <input type="number"
                   class="form-control form-control-sm text-end score-input-modal"
                   name="scores[${criteriaId}][DETAIL][${d.id}]"
                   min="0" max="100" step="0.01" placeholder="0"
                   data-weight="${d.percentage}">
          </td>
        `;
            tbody.appendChild(tr);
        });

        const totalEl = document.getElementById('scoreWeightedTotal');
        if (totalEl) totalEl.textContent = '0.00';
    }

    function recalcModalTotal() {
        let weighted = 0;
        document.querySelectorAll('#scoreRows .score-input-modal').forEach(inp => {
            const v = parseFloat(inp.value || '0');
            const w = parseFloat(inp.dataset.weight || '0');
            weighted += (v / 100) * w;
        });
        const totalEl = document.getElementById('scoreWeightedTotal');
        if (totalEl) totalEl.textContent = weighted.toFixed(2);
    }

    document.addEventListener('input', (e) => {
        if (e.target && e.target.classList.contains('score-input-modal')) {
            recalcModalTotal();
        }
    });

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-open-score');
        if (!btn) return;

        const criteriaId = getActiveCriteriaId();
        if (!criteriaId) return;

        const pid = btn.getAttribute('data-participant-id');
        const pname = btn.getAttribute('data-participant-name');

        document.getElementById('scoreParticipantId').value = pid;
        document.getElementById('scoreParticipantName').textContent = pname;
        document.getElementById('scoreCriteriaId').value = criteriaId;
        document.getElementById('scoreModalCriteriaName').textContent =
            (window.CRITERIA_DETAILS?. [criteriaId]?.name || 'Criteria');

        renderScoreRows(criteriaId);

        const modal = new bootstrap.Modal(document.getElementById('scoreModal'));
        modal.show();
    });
})();
</script>
@endpush