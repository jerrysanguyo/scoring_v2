<div class="modal fade" id="scoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold mb-0">
                    <i class="ki-duotone ki-edit fs-2 me-2 text-primary"></i>
                    Score – <span id="scoreModalCriteriaName" class="text-gray-800"></span>
                </h2>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body py-7 px-8">
                {{-- ✅ These fields fix your error --}}
                <div class="mb-5">
                    <div class="fw-semibold">Participant:</div>
                    <div id="scoreParticipantName" class="text-gray-700"></div>
                </div>

                {{-- Hidden inputs so JS can assign IDs --}}
                <input type="hidden" id="scoreParticipantId" name="participant_id">
                <input type="hidden" id="scoreCriteriaId" name="criteria_id">

                {{-- Table where JS will render criteria details --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle gs-0 gy-3 table-rounded kt-table">
                        <thead>
                            <tr class="bg-light kt-thead">
                                <th class="ps-4 w-50px">#</th>
                                <th class="text-gray-700 text-uppercase fw-bold fs-7">Criteria Detail</th>
                                <th class="text-center text-gray-700 text-uppercase fw-bold fs-7">Weight</th>
                                <th class="pe-4 text-end text-gray-700 text-uppercase fw-bold fs-7">Score</th>
                            </tr>
                        </thead>
                        <tbody id="scoreRows">
                            <!-- JS inserts rows here -->
                        </tbody>
                        <tfoot>
                            <tr class="bg-light-subtle">
                                <td></td>
                                <td class="text-end fw-bold text-gray-700">Weighted Total</td>
                                <td></td>
                                <td class="pe-4 text-end">
                                    <span id="scoreWeightedTotal"
                                        class="badge rounded-pill bg-light-primary text-primary fw-semibold px-3">0.00</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>