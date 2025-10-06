<div class="modal fade" id="deleteCriteriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="mb-0">
                    <i class="ki-duotone ki-trash fs-2 me-2 text-danger"></i>
                    Delete Criteria
                </h2>
                <div class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body py-6 px-8">
                <div id="del-crit-error" class="alert alert-danger d-none mb-6"></div>
                
                <div class="mb-5">
                    <div class="fs-6 text-muted">You are about to delete the following criteria and all its details:
                    </div>
                </div>
                
                <div class="border rounded p-4 mb-6 bg-light">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="mb-3 mb-md-0">
                            <div class="fw-bold fs-5" id="del-crit-name">—</div>
                            <div class="text-muted small">
                                Participants: <span id="del-crit-participants">—</span>
                                &nbsp;•&nbsp;
                                Remarks: <span id="del-crit-remarks">—</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge badge-light-danger">This action cannot be undone</span>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-none border">
                    <div class="card-header border-0 py-3">
                        <h3 class="card-title fw-bold mb-0">Criteria Details</h3>
                        <div class="card-toolbar">
                            <span class="badge badge-light-primary">
                                Total %: <span id="del-crit-total">0</span>
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div id="del-crit-loading" class="d-flex align-items-center">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Loading…</span>
                        </div>

                        <div id="del-crit-empty" class="text-muted d-none">No details found.</div>

                        <div id="del-crit-list" class="table-responsive d-none">
                            <table class="table align-middle table-row-dashed">
                                <thead>
                                    <tr class="text-muted fw-semibold">
                                        <th style="width: 60%">Name</th>
                                        <th class="text-end" style="width: 40%">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody id="del-crit-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between px-8 pb-6">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Cancel
                </button>

                <form id="del-crit-form" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ki-duotone ki-trash fs-3 me-2"></i> Yes, delete it
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal   = document.getElementById('deleteCriteriaModal');
    const nameEl  = document.getElementById('del-crit-name');
    const partEl  = document.getElementById('del-crit-participants');
    const remEl   = document.getElementById('del-crit-remarks');
    const totalEl = document.getElementById('del-crit-total');
    const loadEl  = document.getElementById('del-crit-loading');
    const listEl  = document.getElementById('del-crit-list');
    const bodyEl  = document.getElementById('del-crit-tbody');
    const emptyEl = document.getElementById('del-crit-empty');
    const errEl   = document.getElementById('del-crit-error');
    const formEl  = document.getElementById('del-crit-form');

    function resetModal() {
        errEl.classList.add('d-none');
        errEl.textContent = '';
        nameEl.textContent = partEl.textContent = remEl.textContent = '—';
        totalEl.textContent = '0';
        bodyEl.innerHTML = '';
        loadEl.classList.remove('d-none');
        listEl.classList.add('d-none');
        emptyEl.classList.add('d-none');
        formEl.setAttribute('action', '#');
    }
    
    modal.addEventListener('show.bs.modal', async function (event) {
        const btn = event.relatedTarget;
        if (!btn) return;

        resetModal();

        const fetchUrl  = btn.getAttribute('data-fetch');
        const deleteUrl = btn.getAttribute('data-delete');
        const inlineName = btn.getAttribute('data-name') || '';

        if (inlineName) nameEl.textContent = inlineName;
        formEl.setAttribute('action', deleteUrl || '#');

        try {
            const res = await fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error('Failed to fetch criteria.');

            const data = await res.json();

            nameEl.textContent = data.name ?? inlineName ?? '—';
            partEl.textContent = data.no_of_participants ?? '—';
            remEl.textContent  = (data.remarks ?? '').trim() || '—';

            const details = Array.isArray(data.details) ? data.details : [];
            let total = 0;

            if (details.length === 0) {
                emptyEl.classList.remove('d-none');
            } else {
                details.forEach(d => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${(d.criteria_name ?? '').toString().replace(/</g,'&lt;')}</td>
                        <td class="text-end">${Number(d.percentage ?? 0)}</td>
                    `;
                    bodyEl.appendChild(tr);
                    total += Number(d.percentage ?? 0);
                });
                totalEl.textContent = total;
                listEl.classList.remove('d-none');
            }
        } catch (err) {
            errEl.textContent = String(err.message || err);
            errEl.classList.remove('d-none');
        } finally {
            loadEl.classList.add('d-none');
        }
    });

    modal.addEventListener('hidden.bs.modal', resetModal);
});
</script>
@endpush
