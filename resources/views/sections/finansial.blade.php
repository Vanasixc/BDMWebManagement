@extends('layouts.app')
@section('title', 'Finansial')
@section('page_title', 'Finansial')
@section('page_subtitle', 'Kelola data keuangan, margin, dan status pembayaran.')
@section('content')
@include('components.data-table')

@endsection
@push('scripts')
<script>
window.WHSection   = 'finansial';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
