@extends('layouts.app')
@section('title', 'Hosting')
@section('page_title', 'Hosting')
@section('page_subtitle', 'Kelola data hosting & server seluruh website client.')
@section('content')
@include('components.data-table')

@endsection
@push('scripts')
<script>
window.WHSection   = 'hosting';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
