@extends('layouts.app')

@section('title', 'Master Table')
@section('page_title', 'Master Table')
@section('page_subtitle', 'Kelola data master website client perusahaan.')

@section('content')
@include('components.data-table')

@endsection

@push('scripts')
<script>
window.WHSection   = 'master';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
