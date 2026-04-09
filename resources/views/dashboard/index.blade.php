@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan data infrastruktur website Anda.')
@section('meta_description', 'Dashboard WH Manager — pantau status website, expiry, dan performa finansial.')

@section('content')
{{-- ============================================
     STAT CARDS
     ============================================ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6 stagger-children">
    {{-- Aktif --}}
    <div class="p-5 md:p-6 rounded-xl shadow-sm border flex items-center justify-between
                bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Website Active</p>
            <h3 class="text-3xl font-extrabold mt-1 tabular-nums">{{ $stats['active'] }}</h3>
        </div>
        <div class="p-3.5 rounded-xl bg-emerald-500 shadow-lg shadow-emerald-500/30">
            @include('components.icon', ['name' => 'check-circle', 'class' => 'w-6 h-6 text-white'])
        </div>
    </div>

    {{-- InActive --}}
    <div class="p-5 md:p-6 rounded-xl shadow-sm border flex items-center justify-between
                bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">InActive</p>
            <h3 class="text-3xl font-extrabold mt-1 tabular-nums">{{ $stats['inactive'] }}</h3>
        </div>
        <div class="p-3.5 rounded-xl bg-amber-500 shadow-lg shadow-amber-500/30">
            @include('components.icon', ['name' => 'clock', 'class' => 'w-6 h-6 text-white'])
        </div>
    </div>

    {{-- Suspend --}}
    <div class="p-5 md:p-6 rounded-xl shadow-sm border flex items-center justify-between
                bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Suspend</p>
            <h3 class="text-3xl font-extrabold mt-1 tabular-nums">{{ $stats['suspend'] }}</h3>
        </div>
        <div class="p-3.5 rounded-xl bg-rose-500 shadow-lg shadow-rose-500/30">
            @include('components.icon', ['name' => 'alert-triangle', 'class' => 'w-6 h-6 text-white'])
        </div>
    </div>
</div>

{{-- ============================================
     EXPIRING & EXPIRED TABLES
     ============================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Akan Expired --}}
    <div class="p-5 md:p-6 rounded-xl shadow-sm border bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-4 flex items-center gap-2 text-amber-600 dark:text-amber-400">
            @include('components.icon', ['name' => 'clock', 'class' => 'w-5 h-5'])
            Akan Expired (30 Hari)
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                        <th class="py-2">Client</th>
                        <th class="py-2">Tgl Expired</th>
                        <th class="py-2 text-right">Sisa Hari</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expiring as $item)
                    <tr class="border-b last:border-0 border-gray-100 dark:border-slate-700">
                        <td class="py-2 font-medium">{{ $item->client }}</td>
                        <td class="py-2 text-amber-600 dark:text-amber-400 font-semibold">
                            {{ $item->hosting_exp_date->format('d/m/Y') }}
                        </td>
                        <td class="py-2 text-right font-bold tabular-nums">{{ $item->days_remaining }} Hari</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-400 dark:text-slate-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Sudah Expired --}}
    <div class="p-5 md:p-6 rounded-xl shadow-sm border bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-4 flex items-center gap-2 text-rose-600 dark:text-rose-400">
            @include('components.icon', ['name' => 'alert-triangle', 'class' => 'w-5 h-5'])
            Sudah Expired (Telat Bayar)
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                        <th class="py-2">Client</th>
                        <th class="py-2">Tgl Expired</th>
                        <th class="py-2 text-right">Terlewat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expired as $item)
                    <tr class="border-b last:border-0 border-gray-100 dark:border-slate-700">
                        <td class="py-2 font-medium">{{ $item->client }}</td>
                        <td class="py-2 text-rose-600 dark:text-rose-400 font-semibold">
                            {{ $item->hosting_exp_date->format('d/m/Y') }}
                        </td>
                        <td class="py-2 text-right font-bold tabular-nums">{{ abs($item->days_remaining) }} Hari</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-400 dark:text-slate-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================
     CHARTS
     ============================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Revenue Bar Chart --}}
    <div class="lg:col-span-2 p-5 md:p-6 rounded-xl shadow-sm border bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-5">Progres Pendapatan & Margin</h3>
        <div class="h-56 md:h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Status Pie Chart --}}
    <div class="p-5 md:p-6 rounded-xl shadow-sm border bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-5">Status Website</h3>
        <div class="h-56 md:h-64 flex items-center justify-center">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
const isDark = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(51,65,85,0.8)' : 'rgba(226,232,240,0.8)';
const textColor = isDark ? '#94a3b8' : '#64748b';

new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(collect($revenueData)->pluck('year')) !!},
        datasets: [
            {
                label: 'Total Revenue',
                data: {!! json_encode(collect($revenueData)->pluck('revenue')) !!},
                backgroundColor: 'rgba(59,130,246,0.85)',
                borderRadius: 6,
            },
            {
                label: 'Total Margin',
                data: {!! json_encode(collect($revenueData)->pluck('margin')) !!},
                backgroundColor: 'rgba(16,185,129,0.85)',
                borderRadius: 6,
            },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: textColor, font: { size: 12 } } },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw),
                }
            }
        },
        scales: {
            x: { ticks: { color: textColor, font: { size: 12 } }, grid: { color: gridColor } },
            y: { ticks: { color: textColor, font: { size: 11 }, callback: v => 'Rp ' + (v/1e6) + 'jt' }, grid: { color: gridColor } },
        }
    }
});

// Status Pie Chart
const statusCtx = document.getElementById('statusChart');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'InActive', 'Suspend'],
        datasets: [{
            data: [{{ $stats['active'] }}, {{ $stats['inactive'] }}, {{ $stats['suspend'] }}],
            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { color: textColor, font: { size: 12 }, padding: 16 } },
        },
        cutout: '65%',
    }
});
</script>
@endpush
