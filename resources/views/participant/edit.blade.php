<div class="modal fade" id="edit{{ $resource }}Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            
            <div class="modal-header">
                <h2 class="fw-bold mb-0">
                    <i class="ki-duotone ki-pencil fs-2 me-2 text-warning"></i>
                    Edit {{ $page_title }}
                </h2>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </div>
            </div>
            
            <div class="modal-body py-8 px-10">
                <form id="edit{{ $resource }}Form" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-secondary d-flex align-items-center gap-2 py-2 px-3 mb-6">
                        <i class="ki-duotone ki-information-2 fs-2x me-1"></i>
                        <div>
                            <div class="fw-semibold">Editing:</div>
                            <div id="edit-{{ $resource }}-label" class="text-gray-700 small"></div>
                        </div>
                    </div>
                    
                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Name</label>
                        <input type="text" name="name" id="edit-{{ $resource }}-name"
                            class="form-control form-control-solid" placeholder="Enter name" required>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="ki-duotone ki-check fs-2 me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>