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
                    
                    @if ($resource === 'participant')
                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Name</label>
                        <input type="text" name="name" id="edit-{{ $resource }}-name"
                            class="form-control form-control-solid" placeholder="Enter name" required>
                    </div>
                    @endif
                    
                    @if ($resource === 'account')
                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">First Name</label>
                        <input type="text" name="first_name" id="edit-account-first_name"
                            class="form-control form-control-solid" placeholder="Enter first name" required>
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold fs-6">Middle Name</label>
                        <input type="text" name="middle_name" id="edit-account-middle_name"
                            class="form-control form-control-solid" placeholder="Enter middle name (optional)">
                    </div>

                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Last Name</label>
                        <input type="text" name="last_name" id="edit-account-last_name"
                            class="form-control form-control-solid" placeholder="Enter last name" required>
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold fs-6">Email</label>
                        <input type="email" name="email" id="edit-account-email" class="form-control form-control-solid"
                            placeholder="Enter email (optional)">
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold fs-6">Contact Number</label>
                        <input type="text" name="contact_number" id="edit-account-contact_number"
                            class="form-control form-control-solid" placeholder="Enter contact number (optional)">
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold fs-6">Password <span class="text-muted">(leave blank to
                                keep)</span></label>
                        <input type="password" name="password" id="edit-account-password"
                            class="form-control form-control-solid" placeholder="New password (optional)">
                    </div>

                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Role</label>
                        <select name="role" id="edit-account-role" class="form-select form-select-solid" required>
                            <option value="">Select role</option>
                            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

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