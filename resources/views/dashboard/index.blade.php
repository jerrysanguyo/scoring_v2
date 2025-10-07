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

                @php
                $isLocked = strtolower(trim((string) $criteria->remarks)) === 'lock';
                @endphp

                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
                    <div class="mb-2 mb-md-0">
                        <div class="fw-bold fs-5 text-gray-800">{{ $criteria->name }}</div>
                        <div class="text-muted small">
                            Participants: <span class="fw-semibold">{{ $criteria->no_of_participants }}</span>
                            @if(!empty($criteria->remarks))
                            • Remarks: <span class="fw-normal">{{ $criteria->remarks }}</span>
                            @endif
                            @php $isLocked = (bool) $criteria->is_locked; @endphp
                            @if($isLocked)
                            • <span class="badge bg-danger">Locked</span>
                            @endif
                        </div>
                    </div>

                    @role('superadmin')
                    <div>
                        <button type="button" class="btn btn-sm {{ $isLocked ? 'btn-success' : 'btn-danger' }}"
                            data-bs-toggle="modal" data-bs-target="#lockCriteriaModal-{{ $criteria->id }}">
                            <i class="ki-duotone {{ $isLocked ? 'ki-unlock' : 'ki-lock' }} fs-5 me-1"></i>
                            {{ $isLocked ? 'Unlock' : 'Lock' }}
                        </button>
                        @include('dashboard.modal.lock')
                    </div>
                    @endrole
                </div>

                @include('dashboard.table.participants')

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
        const m = activeBtn.getAttribute('data-bs-target')?.match(/^#criteria-(\d+)$/);
        return m ? parseInt(m[1], 10) : null;
    }

    function recalcModalTotal() {
        let sum = 0;
        document.querySelectorAll('#scoreRows .score-input-modal').forEach(inp => {
            const v = parseFloat(inp.value);
            if (Number.isFinite(v)) sum += v;
        });
        const el = document.getElementById('scoreWeightedTotal');
        if (el) el.textContent = sum.toFixed(2);
    }

    function renderScoreRows(criteriaId) {
        const cfg = window.CRITERIA_DETAILS?. [criteriaId];
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
                 min="0" max="${d.percentage}" step="0.01" placeholder="0"
                 data-weight="${d.percentage}">
        </td>
      `;
            tbody.appendChild(tr);
        });

        recalcModalTotal();
    }

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

        const prefillTemplate =
            `{{ route(Auth::user()->getRoleNames()->first() . '.scores.showForCriteria', ['participant' => '__PID__', 'criteria' => '__CID__']) }}`;
        const prefillUrl = prefillTemplate.replace('__PID__', pid).replace('__CID__', criteriaId);

        fetch(prefillUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(({
                scores
            }) => {
                document.querySelectorAll('#scoreRows .score-input-modal').forEach(inp => {
                    const m = inp.name.match(/\[DETAIL]\[(\d+)\]$/);
                    const detailId = m ? m[1] : null;
                    if (detailId && scores && scores[detailId] !== undefined) {
                        inp.value = scores[detailId];
                    }
                });
                recalcModalTotal();
            })
            .catch(() => {

            });
    });

    document.addEventListener('input', function(e) {
        if (!e.target.classList.contains('score-input-modal')) return;
        const max = parseFloat(e.target.max);
        let val = parseFloat(e.target.value);
        if (!Number.isFinite(val)) val = 0;
        if (Number.isFinite(max)) {
            if (val > max) val = max;
            if (val < 0) val = 0;
        }
        e.target.value = (Math.round(val * 100) / 100).toString();
        recalcModalTotal();
    });
})();

(function() {
    const form = document.getElementById('scoreForm');

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new FormData(form)
        });
        const data = await res.json();

        if (!res.ok) {
            alert(data.message || 'Failed to save scores.');
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('scoreModal'))?.hide();

        const cid = document.getElementById('scoreCriteriaId')?.value;
        sessionStorage.setItem('activeCriteriaTab', cid);
        location.reload();
    });
})();

document.addEventListener('DOMContentLoaded', () => {
    const tabId = sessionStorage.getItem('activeCriteriaTab');
    if (!tabId) return;

    const btn = document.querySelector(`#criteriaTabs [data-bs-target="#criteria-${tabId}"]`);
    if (!btn) return;

    new bootstrap.Tab(btn).show();
    sessionStorage.removeItem('activeCriteriaTab');
});

document.addEventListener('input', function(e) {
    if (e.target.matches('.score-input-modal')) {
        const max = parseFloat(e.target.max);
        const val = parseFloat(e.target.value);
        if (val > max) {
            e.target.value = max;
        } else if (val < 0) {
            e.target.value = 0;
        }
    }
});
</script>
@endpush