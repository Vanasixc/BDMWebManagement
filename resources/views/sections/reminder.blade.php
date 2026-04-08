@extends('layouts.app')
@section('title', 'Reminder')
@section('page_title', 'Reminder')
@section('page_subtitle', 'Pantau status expired domain & hosting seluruh website.')
@section('content')
@include('components.data-table')

@endsection
@push('scripts')
<script>
window.WHSection   = 'reminder';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
