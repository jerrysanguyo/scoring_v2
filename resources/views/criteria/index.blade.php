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
                        <td class="align-middle">{{ $record->no_of_participants }}</td>
                        <td class="align-middle">
                            @if($record->details->isNotEmpty())
                            @php
                            $colors = ['primary','success','info','warning','danger','dark'];
                            @endphp

                            <div class="progress h-6px bg-light rounded mb-3">
                                @foreach($record->details as $i => $d)
                                @php $color = $colors[$i % count($colors)]; @endphp
                                <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                    style="width: {{ (float)$d->percentage }}%;"
                                    aria-valuenow="{{ (float)$d->percentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                                @endforeach
                            </div>

                            <div class="d-flex flex-wrap gap-3">
                                @foreach($record->details as $i => $d)
                                @php $color = $colors[$i % count($colors)]; @endphp
                                <div class="d-flex align-items-center">
                                    <span class="bullet bullet-dot w-8px h-8px bg-{{ $color }} me-2"></span>
                                    <span class="fs-7 text-gray-700">
                                        {{ $d->criteria_name }} <span class="fw-bold">({{ $d->percentage }}%)</span>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <span class="text-muted">No criteria details</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-primary js-edit-criteria"
                                    data-fetch="{{ route(Auth::user()->getRoleNames()->first().'.criteria.show.json', $record->id) }}">
                                    <i class="ki-duotone ki-pencil fs-4 me-1"></i>Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteCriteriaModal"
                                    data-fetch="{{ route(Auth::user()->getRoleNames()->first().'.criteria.show.json', $record->id) }}"
                                    data-delete="{{ route(Auth::user()->getRoleNames()->first().'.criteria.destroy', $record->id) }}"
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
@include('criteria.create')
@include('criteria.delete')
@include('criteria.edit')
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
</script>
@endpush
@endsection