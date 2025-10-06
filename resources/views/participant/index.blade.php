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
                        <td class="align-middle">{{ $record->id }}</td>
                        <td class="align-middle">{{ $record->name }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-primary js-edit-criteria"
                                    data-bs-toggle="modal" data-bs-target="#edit{{ $resource }}Modal"
                                    data-update="{{ route(Auth::user()->getRoleNames()->first() . '.' . $resource . '.update', $record) }}"
                                    data-name="{{ $record->name }}">
                                    <i class="ki-duotone ki-pencil fs-4 me-1"></i>Edit
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-danger js-open-delete"
                                    data-bs-toggle="modal" data-bs-target="#delete{{ $resource }}Modal"
                                    data-delete="{{ route(Auth::user()->getRoleNames()->first() . '.' . $resource . '.destroy', $record) }}"
                                    data-name="{{ $record->name }}" title="Delete {{ $record->name }}">
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
    $(document).on('click', '.js-edit-criteria', function() {
        const $btn = $(this);
        const action = $btn.data('update') || '';
        const name = $btn.data('name') || '';

        const $form = $('#edit{{ $resource }}Form');
        $form.attr('action', action);

        $('#edit-{{ $resource }}-name').val(name);
        $('#edit-{{ $resource }}-label').text(name);
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