<div class="modal fade" id="add{{ $resource }}Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Create Criteria</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body py-lg-10 px-lg-10">
                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                    id="kt_stepper_criteria">
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
                        <form class="form" id="kt_stepper_criteria_form">
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
                                    <div id="criteria-repeater">
                                        <!-- Repeater row template (will be cloned) -->
                                        <template id="criteria-row-template">
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
                                                        class="btn btn-icon btn-sm btn-light-danger remove-row"
                                                        title="Remove row">
                                                        <i class="ki-duotone ki-trash fs-3">
                                                            <span class="path1"></span><span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- initial row (visible) -->
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
                                                    class="btn btn-icon btn-sm btn-light-danger remove-row"
                                                    title="Remove row">
                                                    <i class="ki-duotone ki-trash fs-3">
                                                        <span class="path1"></span><span class="path2"></span>
                                                    </i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- add button -->
                                        <div class="d-flex">
                                            <button type="button" id="add-criteria-row" class="btn btn-sm btn-primary">
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
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stepperEl = document.querySelector('#kt_stepper_criteria');
    const stepper = new KTStepper(stepperEl);

    stepper.on("kt.stepper.next", function(stepperObj) {
        if (stepperObj.getCurrentStepIndex() === 1) {
            let title = document.querySelector('[name="criteria_title"]').value.trim();
            let participants = document.querySelector('[name="participants"]').value.trim();

            if (title === '' || participants === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Required Fields',
                    text: 'Please enter both the Criteria Title and number of Participants before continuing.',
                    confirmButtonColor: '#3085d6'
                });

                stepperObj.goTo(stepperObj.getCurrentStepIndex());
                return;
            }
        }

        stepperObj.goNext();
    });

    stepper.on("kt.stepper.previous", () => stepper.goPrevious());

    stepper.on("kt.stepper.submit", function(e) {
        const percentages = document.querySelectorAll('input[name="percentage[]"]');
        let total = 0;

        percentages.forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        if (Math.round(total) !== 100) {
            Swal.fire({
                icon: 'error',
                title: 'Total Percentage Error',
                text: 'The total percentage must be exactly 100%. Please adjust the values.',
            });
            return;
        }

        document.querySelector('#kt_stepper_criteria_form').submit();
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const repeater = document.getElementById('criteria-repeater');
    const template = document.getElementById('criteria-row-template').content;
    const addBtn = document.getElementById('add-criteria-row');

    function updateSubmitButtonVisibility() {
        const percentages = document.querySelectorAll('input[name="percentage[]"]');
        let total = 0;

        percentages.forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const submitBtn = document.querySelector('[data-kt-stepper-action="submit"]');
        const nextBtn = document.querySelector('[data-kt-stepper-action="next"]');

        if (Math.round(total) === 100) {
            submitBtn.classList.remove('d-none');
            nextBtn.classList.add('d-none');
        } else {
            submitBtn.classList.add('d-none');
            nextBtn.classList.remove('d-none');
        }
    }

    function refreshRemoveButtons() {
        const rows = repeater.querySelectorAll('.criteria-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.remove-row');
            if (!btn) return;
            if (rows.length === 1) {
                btn.classList.add('disabled');
                btn.setAttribute('disabled', 'disabled');
                btn.setAttribute('title', 'At least one row is required');
            } else {
                btn.classList.remove('disabled');
                btn.removeAttribute('disabled');
                btn.setAttribute('title', 'Remove row');
            }
        });
    }

    refreshRemoveButtons();

    addBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const clone = document.importNode(template, true);
        const newRow = clone.querySelector('.criteria-row');
        repeater.insertBefore(clone, addBtn.parentElement);
        refreshRemoveButtons();
        updateSubmitButtonVisibility();
    });

    repeater.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const btn = e.target.closest('.remove-row');
            const row = btn.closest('.criteria-row');
            const rows = repeater.querySelectorAll('.criteria-row');
            if (rows.length === 1) {
                return;
            }
            row.remove();
            refreshRemoveButtons();
            updateSubmitButtonVisibility();
        }
    });

    repeater.addEventListener('input', function(e) {
        const input = e.target;
        if (input.name === 'percentage[]') {
            if (input.value < 0) input.value = 0;
            if (input.value > 100) input.value = 100;
            updateSubmitButtonVisibility(); // 👈 call here
        }
    });
});
</script>
@endpush