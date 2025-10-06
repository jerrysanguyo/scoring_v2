<div class="modal fade" id="delete{{ $resource }}Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            
            <div class="modal-header">
                <h2 class="mb-0">
                    <i class="ki-duotone ki-trash fs-2 me-2 text-danger"></i>
                    Delete {{ $page_title }}
                </h2>
                <div class="btn btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            
            <div class="modal-body py-6 px-8">
                <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                    <i class="ki-duotone ki-shield-cross fs-2x"></i>
                    <div>
                        <div class="fw-bold">Are you sure you want to delete this?</div>
                        <div class="small text-gray-700">
                            This action cannot be undone. Item:
                            <span id="delete-{{ $resource }}-name" class="fw-semibold"></span>
                        </div>
                    </div>
                </div>

                <form id="delete{{ $resource }}Form" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <div class="text-end">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="ki-duotone ki-trash fs-2 me-1"></i> Delete
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>