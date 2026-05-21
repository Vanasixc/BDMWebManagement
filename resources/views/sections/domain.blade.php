@extends('layouts.app')

@section('title', 'Domain')
@section('page_title', 'Domain')
@section('page_subtitle', 'List domain website client')

@section('content')

{{-- Provider Distribution Chart --}}
<div class="p-5 rounded-md border shadow-sm bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700 mb-6">
        <h3 class="font-semibold text-sm mb-4 text-gray-700 dark:text-gray-300">Distribusi Domain Provider</h3>
        @if(count($statsData['providers']) > 0)
        <div class="space-y-3">
            @php
                $totalProviders = array_sum($statsData['providers']);
                $colors = ['#3B82F6','#10B981','#F59E0B','#8B5CF6','#EF4444','#06B6D4'];
                $pi = 0;
            @endphp
            @foreach($statsData['providers'] as $provider => $count)
            @php $pct = $totalProviders > 0 ? round($count / $totalProviders * 100) : 0; @endphp
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background: {{ $colors[$pi % count($colors)] }}"></div>
                <span class="text-sm w-32 truncate shrink-0 text-gray-600 dark:text-gray-300" title="{{ $provider }}">{{ $provider ?: 'Tidak diisi' }}</span>
                <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         style="width: {{ $pct }}%; background: {{ $colors[$pi % count($colors)] }}"></div>
                </div>
                <span class="text-xs font-bold tabular-nums w-8 text-right text-gray-700 dark:text-gray-300">{{ $count }}</span>
                <span class="text-xs text-gray-400 w-8 text-right">{{ $pct }}%</span>
            </div>
            @php $pi++; @endphp
            @endforeach
        </div>
        @else
        <div class="h-32 flex items-center justify-center text-gray-400 dark:text-gray-500 text-sm">
            Belum ada data provider domain.
        </div>
        @endif
    </div>

@include('components.data-table')
@endsection

@push('scripts')
<script>
window.WHSection   = 'domain';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
