<div class="modal fade" id="add{{ $resource }}Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="fw-bold mb-0">
                    <i class="ki-duotone ki-plus-circle fs-2 me-2 text-primary"></i>
                    Add {{ $page_title }}
                </h2>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body py-8 px-10">
                <form id="add{{ $resource }}Form" method="POST"
                    action="{{ route(Auth::user()->getRoleNames()->first() . '.' .$resource . '.store') }}">
                    @csrf
                    
                    @if ($resource === 'participant')
                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Name</label>
                        <input type="text" name="name" class="form-control form-control-solid" placeholder="Enter name"
                            value="{{ old('name') }}" required>
                    </div>
                    @endif
                    
                    @if ($resource === 'account')
                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">First Name</label>
                        <input type="text" name="first_name" class="form-control form-control-solid"
                            placeholder="Enter first name" value="{{ old('first_name') }}" required>
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold fs-6">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control form-control-solid"
                            placeholder="Enter middle name (optional)" value="{{ old('middle_name') }}">
                    </div>

                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Last Name</label>
                        <input type="text" name="last_name" class="form-control form-control-solid"
                            placeholder="Enter last name" value="{{ old('last_name') }}" required>
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold fs-6">Email</label>
                        <input type="email" name="email" class="form-control form-control-solid"
                            placeholder="Enter email (optional)" value="{{ old('email') }}">
                    </div>

                    <div class="mb-7">
                        <label class="form-label fw-semibold fs-6">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control form-control-solid"
                            placeholder="Enter contact number (optional)" value="{{ old('contact_number') }}">
                    </div>

                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Password</label>
                        <input type="password" name="password" class="form-control form-control-solid"
                            placeholder="Enter password" required>
                    </div>

                    <div class="mb-7">
                        <label class="form-label required fw-semibold fs-6">Role</label>
                        <select name="role" class="form-select form-select-solid" required>
                            <option value="">Select role</option>
                            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="text-end">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2 me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>