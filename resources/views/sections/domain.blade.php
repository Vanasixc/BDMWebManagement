@extends('layouts.app')

@section('title', 'Domain')
@section('page_title', 'Domain')
@section('page_subtitle', 'Kelola data domain seluruh website client.')

@section('content')
{{-- Domain Price Chart --}}
<div class="p-5 md:p-6 rounded-xl shadow-sm border mb-6 bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
    <h3 class="font-bold text-sm md:text-base mb-4">Grafik Biaya Domain Per Website</h3>
    <div class="h-40">
        <canvas id="domainChart"></canvas>
    </div>
</div>

@include('components.data-table')

@endsection

@push('scripts')
<script>
window.WHSection   = 'domain';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));

// Domain chart
const isDark = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(51,65,85,0.8)' : 'rgba(226,232,240,0.8)';
const textColor = isDark ? '#94a3b8' : '#64748b';

new Chart(document.getElementById('domainChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($websites->pluck('client')) !!},
        datasets: [{
            label: 'Harga Domain/Thn',
            data: {!! json_encode($websites->pluck('domain_price')) !!},
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59,130,246,0.1)',
            borderWidth: 2,
            pointRadius: 4,
            fill: true,
            tension: 0.3,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) } }
        },
        scales: {
            x: { ticks: { color: textColor, font: { size: 11 } }, grid: { color: gridColor } },
            y: { ticks: { color: textColor, font: { size: 11 }, callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }, grid: { color: gridColor } },
        }
    }
});
</script>
@endpush
