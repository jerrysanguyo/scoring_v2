@extends('layouts.dashboard')
@section('content')
@include('components.alert')

<div class="card shadow-lg card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="font-weight-bold mb-0">
            List of {{ $page_title }}
        </h3>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add{{ $resource }}Modal">
            <i class="fa fa-plus"></i> Add {{ $page_title }}
        </button>
    </div>
    <div class="card-body">
        <div class="">
            <table id="{{ $resource }}-table"
                class="table table-hover table-responsive table-striped align-middle text-center display nowrap w-100">
                <thead
                    class="text-gray-800 fw-bold fs-6 text-uppercase bg-light-primary border-bottom border-gray-300 text-center">
                    <tr>
                        @foreach ($columns as $column)
                        <th class="px-4 py-3">
                            {{ $column }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $record)
                    <tr>
                        @if ($resource === 'participant')
                        <td class="align-middle">{{ $record->id }}</td>
                        <td class="align-middle">{{ $record->name }}</td>
                        @endif
                        @if ($resource === 'account')
                        <td class="align-middle">{{ $record->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span>{{ trim($record->first_name.' '.($record->middle_name ?? '').' '.$record->last_name) }}</span>

                                @if ($record->role_name === 'superadmin')
                                <span class="badge bg-danger fw-semibold">{{ ucfirst($record->role_name) }}</span>
                                @elseif ($record->role_name === 'admin')
                                <span class="badge bg-primary fw-semibold">{{ ucfirst($record->role_name) }}</span>
                                @else
                                <span class="badge bg-secondary fw-semibold">{{ ucfirst($record->role_name) }}</span>
                                @endif
                            </div>
                        </td>
                        @endif
                        <td class="align-middle">
                            <div class="btn-group" role="group">
                                @if ($resource === 'participant')
                                <button type="button" class="btn btn-sm btn-primary js-edit-participant"
                                    data-bs-toggle="modal" data-bs-target="#edit{{ $resource }}Modal"
                                    data-update="{{ route(Auth::user()->getRoleNames()->first() . '.' . $resource . '.update', $record) }}"
                                    data-name="{{ $record->name }}">
                                    <i class="ki-duotone ki-pencil fs-4 me-1"></i>Edit
                                </button>
                                @endif

                                @if ($resource === 'account')
                                <button type="button" class="btn btn-sm btn-primary js-edit-account"
                                    data-bs-toggle="modal" data-bs-target="#edit{{ $resource }}Modal"
                                    data-update="{{ route(Auth::user()->getRoleNames()->first() . '.' . $resource . '.update', $record) }}"
                                    data-first_name="{{ $record->first_name }}"
                                    data-middle_name="{{ $record->middle_name }}"
                                    data-last_name="{{ $record->last_name }}" data-email="{{ $record->email }}"
                                    data-contact_number="{{ $record->contact_number }}"
                                    data-role="{{ $record->role_name }}"
                                    data-label="{{ trim($record->first_name.' '.($record->middle_name ?? '').' '.$record->last_name) }}">
                                    <i class=" ki-duotone ki-pencil fs-4 me-1"></i>Edit
                                </button>
                                @endif

                                <button type="button" class="btn btn-sm btn-danger js-open-delete"
                                    data-bs-toggle="modal" data-bs-target="#delete{{ $resource }}Modal"
                                    data-delete="{{ route(Auth::user()->getRoleNames()->first() . '.' . $resource . '.destroy', $record) }}"
                                    @if ($resource==='participant' ) data-name="{{ $record->name }}"
                                    title="Delete {{ $record->name }}" @else data-name="{{ trim($record->first_name.' '.($record->middle_name ?? '').' '.$record->last_name) }}"
                                    title="Delete {{ trim($record->first_name.' '.($record->middle_name ?? '').' '.$record->last_name) }}" @endif>
                                    <i class="ki-duotone ki-trash fs-3"></i>Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('modals')
@include('participant.create')
@include('participant.delete')
@include('participant.edit')
@endpush
@push('scripts')
<script>
$(document).ready(function() {
    const table = $('#{{ $resource }}-table').DataTable({
        processing: true,
        serverSide: false,
        pageLength: 10,
        responsive: true,
        scrollX: true,

        order: [
            [0, 'desc']
        ],

        dom: '<"row mb-3"' +
            '<"col-sm-6 d-flex align-items-center"l>' +
            '<"col-sm-6 d-flex justify-content-end align-items-center"f>' +
            '>rt' +
            '<"row mt-3"' +
            '<"col-sm-6"i>' +
            '<"col-sm-6 text-end"p>' +
            '>',

        initComplete() {
            const $length = $('div.dataTables_length');
            $length.find('label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();

            $length.prepend('<label class="me-2 mb-0 fw-semibold">Show:</label>');
            $length.find('select')
                .addClass(
                    'form-select form-select-sm me-2 text-gray-800 rounded border border-gray-300 shadow-sm'
                )
                .css('min-width', '50px', );

            const $filter = $('div.dataTables_filter');
            $filter.find('label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();

            $filter.prepend('<label class="me-2 mb-0 fw-semibold">Search:</label>');
            $filter.find('input')
                .addClass('form-control form-control-sm w-auto text-gray-800');
        },
    });
});

document.addEventListener('DOMContentLoaded', function() {
    $(document).on('click', '.js-edit-participant', function() {
        const $btn = $(this);
        const action = $btn.data('update') || '';
        const name = $btn.data('name') || '';

        const $form = $('#editparticipantForm');
        $form.attr('action', action);

        $('#edit-participant-name').val(name);
        $('#edit-participant-label').text(name);
    });

    $(document).on('click', '.js-edit-account', function() {
        const $btn = $(this);
        const action = $btn.data('update') || '';
        const label = $btn.data('label') || '';

        const $form = $('#editaccountForm');
        $form.attr('action', action);

        $('#edit-account-first_name').val($btn.data('first_name') || '');
        $('#edit-account-middle_name').val($btn.data('middle_name') || '');
        $('#edit-account-last_name').val($btn.data('last_name') || '');
        $('#edit-account-email').val($btn.data('email') || '');
        $('#edit-account-contact_number').val($btn.data('contact_number') || '');
        $('#edit-account-password').val('');
        $('#edit-account-role').val(($btn.data('role') || '').toLowerCase());

        $('#edit-account-label').text(label);
    });

    $(document).on('click', '.js-open-delete', function() {
        const $btn = $(this);
        const action = $btn.data('delete') || '';
        const name = $btn.data('name') || '';

        const $form = $('#delete{{ $resource }}Form');
        $form.attr('action', action);
        $('#delete-{{ $resource }}-name').text(name);
    });
});
</script>
@endpush
@endsection