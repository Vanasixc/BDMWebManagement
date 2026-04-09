@extends('layouts.app')
@section('title', 'Hosting')
@section('page_title', 'Hosting')
@section('page_subtitle', 'Kelola data hosting & server seluruh website client.')

@section('content')

{{-- ============================================================
     VISUALISASI: Expiry Timeline Cards
     ============================================================ --}}
@if(count($statsData['expiry_cards']) > 0)
<div class="p-5 md:p-6 rounded-xl border shadow-sm mb-6 bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
    <h3 class="font-bold text-sm md:text-base mb-4 flex items-center gap-2">
        @include('components.icon', ['name' => 'calendar', 'class' => 'w-5 h-5 text-blue-500'])
        Status Expired Hosting
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
        @foreach($statsData['expiry_cards'] as $card)
        @php
            $days   = $card['days'];
            $status = $card['status'];
            if ($status === 'Expired') {
                $color = ['card' => 'border-rose-200 bg-rose-50 dark:bg-rose-500/10 dark:border-rose-500/30', 'badge' => 'bg-rose-500 text-white', 'bar' => 'bg-rose-500', 'text' => 'text-rose-700 dark:text-rose-400'];
            } elseif ($status === 'Kritis') {
                $color = ['card' => 'border-rose-200 bg-rose-50 dark:bg-rose-500/10 dark:border-rose-500/30', 'badge' => 'bg-rose-500 text-white', 'bar' => 'bg-rose-500', 'text' => 'text-rose-700 dark:text-rose-400'];
            } elseif ($status === 'Segera') {
                $color = ['card' => 'border-amber-200 bg-amber-50 dark:bg-amber-500/10 dark:border-amber-500/30', 'badge' => 'bg-amber-500 text-white', 'bar' => 'bg-amber-500', 'text' => 'text-amber-700 dark:text-amber-400'];
            } else {
                $color = ['card' => 'border-emerald-200 bg-emerald-50 dark:bg-emerald-500/10 dark:border-emerald-500/30', 'badge' => 'bg-emerald-500 text-white', 'bar' => 'bg-emerald-500', 'text' => 'text-emerald-700 dark:text-emerald-400'];
            }
            // Progress bar: 365 hari = penuh, kurang = mengurang
            $barPct = $days <= 0 ? 0 : min(100, round($days / 365 * 100));
        @endphp
        <div class="p-4 rounded-xl border {{ $color['card'] }} flex flex-col gap-2">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-sm font-bold truncate" title="{{ $card['website'] }}">{{ $card['website'] }}</p>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ $card['client'] }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0 {{ $color['badge'] }}">
                    {{ $status }}
                </span>
            </div>
            <div class="text-xs text-slate-500 dark:text-slate-400">
                Exp: <span class="font-semibold {{ $color['text'] }}">{{ $card['exp_date'] }}</span>
            </div>
            <div class="space-y-1">
                <div class="h-1.5 bg-black/10 dark:bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $color['bar'] }} transition-all duration-700"
                         style="width: {{ $barPct }}%"></div>
                </div>
                <p class="text-[11px] font-bold {{ $color['text'] }} text-right">
                    {{ $days < 0 ? 'Telat '.abs($days).' hari' : $days.' hari lagi' }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@include('components.data-table')
@endsection

@push('scripts')
<script>
window.WHSection   = 'hosting';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
