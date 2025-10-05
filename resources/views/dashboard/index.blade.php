@extends('layouts.dashboard')
@section('content')
@include('components.alert')



@endsection
@push('scripts')
<script src="{{ asset('assets/js/auth/password.js') }}"></script>
@endpush