@extends('layouts.app')
@section('title', 'Akses')
@section('page_title', 'Akses')
@section('page_subtitle', 'Kelola data akses admin & password seluruh website.')
@section('content')
@include('components.data-table')

@endsection
@push('scripts')
<script>
window.WHSection   = 'akses';
window.WHDropdowns = {};
</script>
@endpush
