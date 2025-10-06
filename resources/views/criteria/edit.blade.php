<div class="modal fade" id="editCriteriaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Criteria</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body py-lg-10 px-lg-10">
                <form class="form" id="kt_stepper_criteria_form_edit" action="{{ route(Auth::user()->getRoleNames()->first() . '.criteria.update', $record->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                        id="kt_stepper_criteria_edit">
                        <div class="d-flex justify-content-center flex-row-auto w-100 w-xl-300px">
                            <div class="stepper-nav ps-lg-10">
                                <div class="stepper-item current" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">1</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Criteria's title</h3>
                                            <div class="stepper-desc">Title of criteria & number of participants</div>
                                        </div>
                                    </div>
                                    <div class="stepper-line h-40px"></div>
                                </div>

                                <div class="stepper-item" data-kt-stepper-element="nav">
                                    <div class="stepper-wrapper">
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                            <span class="stepper-number">2</span>
                                        </div>
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">Criteria's Details</h3>
                                            <div class="stepper-desc">Name of criteria & percentage</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex-row-fluid py-lg-5 px-lg-15">
                            <div class="current" data-kt-stepper-element="content">
                                <div class="w-100">
                                    <div class="fv-row mb-10">
                                        <label class="form-label fs-6 fw-semibold mb-2">Criteria Title</label>
                                        <input type="text" class="form-control form-control-lg form-control-solid"
                                            name="criteria_title" placeholder="Enter criteria title" />
                                    </div>
                                    <div class="fv-row">
                                        <label class="form-label fs-6 fw-semibold mb-2">Number of Participants</label>
                                        <input type="number" class="form-control form-control-lg form-control-solid"
                                            name="participants" placeholder="Enter number of participants" />
                                    </div>
                                </div>
                            </div>
                            
                            <div data-kt-stepper-element="content">
                                <div class="w-100">
                                    <div id="criteria-repeater-edit">

                                        <template id="criteria-row-template-edit">
                                            <div class="criteria-row d-flex align-items-center gap-3 mb-4">
                                                <div class="flex-grow-1">
                                                    <label class="form-label fs-6 fw-semibold mb-2">Name</label>
                                                    <input type="text" name="criteria_name[]"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="Enter name" />
                                                </div>

                                                <div style="width:160px;">
                                                    <label class="form-label fs-6 fw-semibold mb-2">Percentage</label>
                                                    <input type="number" name="percentage[]"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="%" min="0" max="100" />
                                                </div>

                                                <div class="d-flex flex-column align-items-center justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-icon btn-sm btn-light-danger remove-row-edit"
                                                        title="Remove row">
                                                        <i class="ki-duotone ki-trash fs-3">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                        
                                        <div class="criteria-row d-flex align-items-center gap-3 mb-4">
                                            <div class="flex-grow-1">
                                                <label class="form-label fs-6 fw-semibold mb-2">Name</label>
                                                <input type="text" name="criteria_name[]"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="Enter name" />
                                            </div>

                                            <div style="width:160px;">
                                                <label class="form-label fs-6 fw-semibold mb-2">Percentage</label>
                                                <input type="number" name="percentage[]"
                                                    class="form-control form-control-lg form-control-solid"
                                                    placeholder="%" min="0" max="100" />
                                            </div>

                                            <div class="d-flex flex-column align-items-center justify-content-end">
                                                <button type="button"
                                                    class="btn btn-icon btn-sm btn-light-danger remove-row-edit"
                                                    title="Remove row">
                                                    <i class="ki-duotone ki-trash fs-3">
                                                        <span class="path1"></span><span class="path2"></span>
                                                    </i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <button type="button" id="add-criteria-row-edit"
                                                class="btn btn-sm btn-primary">
                                                <i class="ki-duotone ki-plus fs-3 me-1"><span class="path1"></span><span
                                                        class="path2"></span></i>
                                                Add Row
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-stack pt-10">
                                <div class="me-2">
                                    <button type="button" class="btn btn-lg btn-light-primary"
                                        data-kt-stepper-action="previous">
                                        <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span
                                                class="path2"></span></i>
                                        Back
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
                                        Continue
                                        <i class="ki-duotone ki-arrow-right fs-3 ms-1 me-0"><span
                                                class="path1"></span><span class="path2"></span></i>
                                    </button>
                                    <button type="submit" class="btn btn-lg btn-success d-none"
                                        data-kt-stepper-action="submit">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div> 
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function sumPercentages(container) {
    if (!container) return 0;
    let total = 0;
    container.querySelectorAll('input[name="percentage[]"]').forEach(i => {
        total += parseFloat(i.value) || 0;
    });
    return Math.round(total);
}

function toggleSubmitVsNext(container) {
    if (!container) return;
    const form = container.closest('form');
    if (!form) return;
    const submitBtn = form.querySelector('[data-kt-stepper-action="submit"]');
    const nextBtn   = form.querySelector('[data-kt-stepper-action="next"]');
    if (!submitBtn || !nextBtn) return;
    const total = sumPercentages(container);
    if (total === 100) {
        submitBtn.classList.remove('d-none');
        nextBtn.classList.add('d-none');
    } else {
        submitBtn.classList.add('d-none');
        nextBtn.classList.remove('d-none');
    }
}

function refreshRemoveButtons(container) {
    if (!container) return;
    const rows = container.querySelectorAll('.criteria-row');
    rows.forEach(row => {
        const btn = row.querySelector('.remove-row-edit');
        if (!btn) return;
        if (rows.length === 1) {
            btn.classList.add('disabled');
            btn.setAttribute('disabled','disabled');
            btn.title = 'At least one row is required';
        } else {
            btn.classList.remove('disabled');
            btn.removeAttribute('disabled');
            btn.title = 'Remove row';
        }
    });
}

document.addEventListener('click', async function(e) {
    const btn = e.target.closest('.js-edit-criteria');
    if (!btn) return;

    const fetchUrl = btn.getAttribute('data-fetch');
    const modalEl  = document.getElementById('editCriteriaModal');
    const formEl   = document.getElementById('kt_stepper_criteria_form_edit');
    const repeater = document.getElementById('criteria-repeater-edit');
    const tpl      = document.getElementById('criteria-row-template-edit')?.content;

    try {
        const res = await fetch(fetchUrl, { headers: { 'X-Requested-With':'XMLHttpRequest' }});
        if (!res.ok) throw new Error('Failed to fetch criteria data.');
        const data = await res.json();
        
        formEl.setAttribute('action', data.update_url || '#');
        
        formEl.querySelector('[name="criteria_title"]').value = data.name || '';
        formEl.querySelector('[name="participants"]').value   = data.no_of_participants || '';
        
        const rows = repeater.querySelectorAll('.criteria-row');
        rows.forEach((row, idx) => {
            if (idx === 0) {
                row.querySelector('input[name="criteria_name[]"]').value = '';
                row.querySelector('input[name="percentage[]"]').value    = '';
            } else {
                row.remove();
            }
        });
        
        const details = Array.isArray(data.details) ? data.details : [];
        if (details.length > 0) {
            const firstRow = repeater.querySelector('.criteria-row');
            firstRow.querySelector('input[name="criteria_name[]"]').value = details[0].criteria_name || '';
            firstRow.querySelector('input[name="percentage[]"]').value    = details[0].percentage ?? '';
            for (let i = 1; i < details.length; i++) {
                const clone = document.importNode(tpl, true);
                const row   = clone.querySelector('.criteria-row');
                row.querySelector('input[name="criteria_name[]"]').value = details[i].criteria_name || '';
                row.querySelector('input[name="percentage[]"]').value    = details[i].percentage ?? '';
                repeater.insertBefore(clone, document.getElementById('add-criteria-row-edit').parentElement);
            }
        }

        refreshRemoveButtons(repeater);
        
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();

    } catch (err) {
        console.error(err);
        Swal.fire({ icon:'error', title:'Unable to load criteria', text:String(err) });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const modalEl  = document.getElementById('editCriteriaModal');
    const formEl   = document.getElementById('kt_stepper_criteria_form_edit');
    const repeater = document.getElementById('criteria-repeater-edit');

    if (!modalEl) return;

    modalEl.addEventListener('shown.bs.modal', function () {
        const stepperEl = document.getElementById('kt_stepper_criteria_edit');
        
        if (stepperEl.__ktStepper) return;

        const stepper = new KTStepper(stepperEl);
        stepperEl.__ktStepper = stepper;
        
        const nextBtn   = formEl.querySelector('[data-kt-stepper-action="next"]');
        const submitBtn = formEl.querySelector('[data-kt-stepper-action="submit"]');
        if (nextBtn)   nextBtn.classList.remove('d-none');
        if (submitBtn) submitBtn.classList.add('d-none');

        stepper.on("kt.stepper.next", function (obj) {
            if (obj.getCurrentStepIndex() === 1) {
                const title = formEl.querySelector('[name="criteria_title"]').value.trim();
                const parts = formEl.querySelector('[name="participants"]').value.trim();
                if (!title || !parts) {
                    Swal.fire({ icon:'error', title:'Missing Required Fields',
                                text:'Please enter both the Criteria Title and number of Participants before continuing.' });
                    return;
                }
            }
            stepper.goNext();
            toggleSubmitVsNext(repeater);
        });

        stepper.on("kt.stepper.previous", function () {
            stepper.goPrevious();
            const nextBtn   = formEl.querySelector('[data-kt-stepper-action="next"]');
            const submitBtn = formEl.querySelector('[data-kt-stepper-action="submit"]');
            if (nextBtn)   nextBtn.classList.remove('d-none');
            if (submitBtn) submitBtn.classList.add('d-none');
        });

        stepper.on("kt.stepper.submit", function (e) {
            e.preventDefault();
            const total = sumPercentages(repeater);
            if (total !== 100) {
                Swal.fire({ icon:'error', title:'Total Percentage Error', text:'The total percentage must be exactly 100%.' });
                return;
            }
            formEl.submit();
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        const stepperEl = document.getElementById('kt_stepper_criteria_edit');
        if (stepperEl && stepperEl.__ktStepper) {
            stepperEl.__ktStepper = null;
        }
    });
});

document.addEventListener('click', function(e){
    const addBtn = e.target.closest('#add-criteria-row-edit');
    if (addBtn) {
        const repeater = document.getElementById('criteria-repeater-edit');
        const tpl = document.getElementById('criteria-row-template-edit').content;
        const clone = document.importNode(tpl, true);
        repeater.insertBefore(clone, addBtn.parentElement);
        refreshRemoveButtons(repeater);
        toggleSubmitVsNext(repeater);
    }
    const rm = e.target.closest('.remove-row-edit');
    if (rm) {
        const repeater = document.getElementById('criteria-repeater-edit');
        const row = rm.closest('.criteria-row');
        const rows = repeater.querySelectorAll('.criteria-row');
        if (rows.length > 1) row.remove();
        refreshRemoveButtons(repeater);
        toggleSubmitVsNext(repeater);
    }
});

document.addEventListener('input', function(e){
    const input = e.target;
    if (input && input.name === 'percentage[]' && input.closest('#criteria-repeater-edit')) {
        if (input.value < 0) input.value = 0;
        if (input.value > 100) input.value = 100;
        toggleSubmitVsNext(document.getElementById('criteria-repeater-edit'));
    }
});
</script>
@endpush
