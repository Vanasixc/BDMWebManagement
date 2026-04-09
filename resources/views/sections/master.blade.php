@extends('layouts.app')

@section('title', 'Master Table')
@section('page_title', 'Master Table')
@section('page_subtitle', 'Kelola data master website client perusahaan.')

@section('content')

{{-- ============================================================
     VISUALISASI: Stat Cards + Doughnut Chart Status Website
     ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Stat Cards --}}
    <div class="lg:col-span-1 grid grid-cols-3 gap-3 content-start">
        {{-- Active --}}
        <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                    bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="w-9 h-9 rounded-xl bg-emerald-500 shadow-lg shadow-emerald-500/30 flex items-center justify-center mb-1">
                @include('components.icon', ['name' => 'check-circle', 'class' => 'w-5 h-5 text-white'])
            </div>
            <p class="text-2xl font-extrabold tabular-nums">{{ $statsData['active'] }}</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Active</p>
        </div>
        {{-- InActive --}}
        <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                    bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="w-9 h-9 rounded-xl bg-amber-500 shadow-lg shadow-amber-500/30 flex items-center justify-center mb-1">
                @include('components.icon', ['name' => 'clock', 'class' => 'w-5 h-5 text-white'])
            </div>
            <p class="text-2xl font-extrabold tabular-nums">{{ $statsData['inactive'] }}</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">InActive</p>
        </div>
        {{-- Suspend --}}
        <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                    bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
            <div class="w-9 h-9 rounded-xl bg-rose-500 shadow-lg shadow-rose-500/30 flex items-center justify-center mb-1">
                @include('components.icon', ['name' => 'alert-triangle', 'class' => 'w-5 h-5 text-white'])
            </div>
            <p class="text-2xl font-extrabold tabular-nums">{{ $statsData['suspend'] }}</p>
            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Suspend</p>
        </div>

        {{-- Total info --}}
        <div class="col-span-3 p-3 rounded-xl border bg-blue-50 border-blue-100 dark:bg-blue-500/10 dark:border-blue-500/20 flex items-center justify-between">
            <span class="text-xs font-semibold text-blue-700 dark:text-blue-300">Total Website Terdaftar</span>
            <span class="text-lg font-extrabold text-blue-700 dark:text-blue-300 tabular-nums">{{ $statsData['total'] }}</span>
        </div>
    </div>

    {{-- Doughnut Chart --}}
    <div class="lg:col-span-2 p-5 rounded-xl border shadow-sm bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-4">Distribusi Status Website</h3>
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="w-40 h-40 shrink-0">
                <canvas id="masterStatusChart"></canvas>
            </div>
            <div class="flex flex-col gap-3 flex-1">
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-sm flex-1">Active</span>
                    <span class="font-bold tabular-nums text-sm">{{ $statsData['active'] }}</span>
                    <span class="text-xs text-slate-400 w-10 text-right">
                        {{ $statsData['total'] > 0 ? round($statsData['active'] / $statsData['total'] * 100) : 0 }}%
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0"></span>
                    <span class="text-sm flex-1">InActive</span>
                    <span class="font-bold tabular-nums text-sm">{{ $statsData['inactive'] }}</span>
                    <span class="text-xs text-slate-400 w-10 text-right">
                        {{ $statsData['total'] > 0 ? round($statsData['inactive'] / $statsData['total'] * 100) : 0 }}%
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-rose-500 shrink-0"></span>
                    <span class="text-sm flex-1">Suspend</span>
                    <span class="font-bold tabular-nums text-sm">{{ $statsData['suspend'] }}</span>
                    <span class="text-xs text-slate-400 w-10 text-right">
                        {{ $statsData['total'] > 0 ? round($statsData['suspend'] / $statsData['total'] * 100) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.data-table')
@endsection

@push('scripts')
<script>
window.WHSection   = 'master';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));

// Doughnut chart status
(function() {
    const isDark    = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    new Chart(document.getElementById('masterStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Active', 'InActive', 'Suspend'],
            datasets: [{
                data: [{{ $statsData['active'] }}, {{ $statsData['inactive'] }}, {{ $statsData['suspend'] }}],
                backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            cutout: '65%',
        }
    });
})();
</script>
@endpush
