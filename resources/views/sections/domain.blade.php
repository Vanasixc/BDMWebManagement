@extends('layouts.app')

@section('title', 'Domain')
@section('page_title', 'Domain')
@section('page_subtitle', 'Kelola data domain seluruh website client.')

@section('content')

{{-- ============================================================
     VISUALISASI: Pricing Tier Cards + Provider Distribution
     ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Pricing Tier Cards --}}
    <div class="lg:col-span-1 flex flex-col gap-4">
        <div class="p-4 rounded-xl border shadow-sm bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Segmentasi Harga Domain/Thn</p>
            <div class="space-y-3">
                {{-- Tier Low --}}
                <div class="flex items-center gap-3 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center shrink-0 shadow shadow-emerald-500/30">
                        @include('components.icon', ['name' => 'tag', 'class' => 'w-4 h-4 text-white'])
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Ekonomis</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Rp 0 – 100.000</p>
                    </div>
                    <span class="text-xl font-extrabold tabular-nums text-emerald-700 dark:text-emerald-400">{{ $statsData['tier_low'] }}</span>
                </div>
                {{-- Tier Mid --}}
                <div class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                    <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center shrink-0 shadow shadow-blue-500/30">
                        @include('components.icon', ['name' => 'tag', 'class' => 'w-4 h-4 text-white'])
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-700 dark:text-blue-400">Standar</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Rp 100.001 – 200.000</p>
                    </div>
                    <span class="text-xl font-extrabold tabular-nums text-blue-700 dark:text-blue-400">{{ $statsData['tier_mid'] }}</span>
                </div>
                {{-- Tier High --}}
                <div class="flex items-center gap-3 p-3 rounded-lg bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20">
                    <div class="w-8 h-8 rounded-lg bg-violet-500 flex items-center justify-center shrink-0 shadow shadow-violet-500/30">
                        @include('components.icon', ['name' => 'tag', 'class' => 'w-4 h-4 text-white'])
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-violet-700 dark:text-violet-400">Premium</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">> Rp 200.000</p>
                    </div>
                    <span class="text-xl font-extrabold tabular-nums text-violet-700 dark:text-violet-400">{{ $statsData['tier_high'] }}</span>
                </div>
                {{-- Avg --}}
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
                    <span class="text-xs text-slate-500 dark:text-slate-400">Rata-rata Harga</span>
                    <span class="text-sm font-bold tabular-nums">Rp {{ number_format($statsData['avg_price'], 0, ',', '.') }}/thn</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Provider Distribution Chart --}}
    <div class="lg:col-span-2 p-5 rounded-xl border shadow-sm bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-4">Distribusi Domain Provider</h3>
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
                <span class="text-sm w-32 truncate shrink-0" title="{{ $provider }}">{{ $provider ?: 'Tidak diisi' }}</span>
                <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         style="width: {{ $pct }}%; background: {{ $colors[$pi % count($colors)] }}"></div>
                </div>
                <span class="text-xs font-bold tabular-nums w-8 text-right">{{ $count }}</span>
                <span class="text-xs text-slate-400 w-8 text-right">{{ $pct }}%</span>
            </div>
            @php $pi++; @endphp
            @endforeach
        </div>
        @else
        <div class="h-32 flex items-center justify-center text-slate-400 dark:text-slate-500 text-sm">
            Belum ada data provider domain.
        </div>
        @endif
    </div>
</div>

@include('components.data-table')
@endsection

@push('scripts')
<script>
window.WHSection   = 'domain';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
