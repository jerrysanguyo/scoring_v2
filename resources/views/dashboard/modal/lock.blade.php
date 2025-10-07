<div class="modal fade" id="lockCriteriaModal-{{ $criteria->id }}" tabindex="-1"
    aria-labelledby="lockCriteriaLabel-{{ $criteria->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="lockCriteriaLabel-{{ $criteria->id }}">
                    <i class="ki-duotone ki-lock fs-3 text-danger me-2"></i> Lock Criteria
                </h5>
                <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>

            <div class="modal-body">
                <p class="mb-4">
                    You are about to <span class="fw-bold text-danger">lock</span> the criteria
                    <span class="fw-semibold text-dark">"{{ $criteria->name }}"</span>.
                    Once locked, no further changes or scoring can be made.
                </p>
                <p class="small text-muted mb-0">
                    This action cannot be undone without administrator permission.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>

                @php $isLocked = (bool) $criteria->is_locked; @endphp

                <form method="POST" action="{{ $isLocked
                    ? route('superadmin.criteria.unlock', $criteria)
                    : route('superadmin.criteria.lock',   $criteria) }}">
                    @csrf
                    <button type="submit" class="btn {{ $isLocked ? 'btn-success' : 'btn-danger' }}">
                        <i class="ki-duotone {{ $isLocked ? 'ki-unlock' : 'ki-check' }} fs-5 me-1"></i>
                        {{ $isLocked ? 'Yes, Unlock It' : 'Yes, Lock It' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>