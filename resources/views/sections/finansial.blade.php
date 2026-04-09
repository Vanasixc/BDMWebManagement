@extends('layouts.app')
@section('title', 'Finansial')
@section('page_title', 'Finansial')
@section('page_subtitle', 'Kelola data keuangan, margin, dan status pembayaran.')

@section('content')

{{-- ============================================================
     VISUALISASI: Summary Cards Finansial
     ============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    @php
        $finCards = [
            ['label' => 'Total Pendapatan', 'value' => $statsData['total_revenue'], 'icon' => 'dollar-sign', 'bg' => 'bg-blue-500', 'shadow' => 'shadow-blue-500/30', 'text' => 'text-blue-700 dark:text-blue-400', 'area' => 'bg-blue-50 border-blue-100 dark:bg-blue-500/10 dark:border-blue-500/20'],
            ['label' => 'Total B. Domain',  'value' => $statsData['total_domain'],  'icon' => 'globe',        'bg' => 'bg-violet-500','shadow' => 'shadow-violet-500/30','text' => 'text-violet-700 dark:text-violet-400','area' => 'bg-violet-50 border-violet-100 dark:bg-violet-500/10 dark:border-violet-500/20'],
            ['label' => 'Total B. Hosting', 'value' => $statsData['total_hosting'], 'icon' => 'server',       'bg' => 'bg-amber-500', 'shadow' => 'shadow-amber-500/30', 'text' => 'text-amber-700 dark:text-amber-400', 'area' => 'bg-amber-50 border-amber-100 dark:bg-amber-500/10 dark:border-amber-500/20'],
            ['label' => 'Total Margin',     'value' => $statsData['total_margin'],  'icon' => 'trending-up',  'bg' => 'bg-emerald-500','shadow' => 'shadow-emerald-500/30','text' => 'text-emerald-700 dark:text-emerald-400','area' => 'bg-emerald-50 border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20'],
        ];
    @endphp
    @foreach($finCards as $card)
    <div class="p-4 md:p-5 rounded-xl border shadow-sm {{ $card['area'] }}">
        <div class="flex items-center justify-between mb-2">
            <p class="text-[10px] font-semibold uppercase tracking-wider {{ $card['text'] }}">{{ $card['label'] }}</p>
            <div class="w-8 h-8 rounded-lg {{ $card['bg'] }} {{ $card['shadow'] }} shadow-lg flex items-center justify-center shrink-0">
                @include('components.icon', ['name' => $card['icon'], 'class' => 'w-4 h-4 text-white'])
            </div>
        </div>
        <p class="text-lg md:text-xl font-extrabold tabular-nums {{ $card['text'] }} leading-tight">
            Rp {{ number_format($card['value'], 0, ',', '.') }}
        </p>
    </div>
    @endforeach
</div>

{{-- ============================================================
     Payment Status Bar + Margin Chart
     ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Payment Status --}}
    <div class="p-5 rounded-xl border shadow-sm bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            @include('components.icon', ['name' => 'credit-card', 'class' => 'w-4 h-4 text-slate-400'])
            Status Pembayaran
        </h3>
        @php
            $totalPay = $statsData['lunas'] + $statsData['belum'];
            $lunasPct = $totalPay > 0 ? round($statsData['lunas'] / $totalPay * 100) : 0;
            $belumPct = 100 - $lunasPct;
        @endphp
        <div class="space-y-4">
            {{-- Lunas --}}
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Lunas</span>
                    <span class="text-sm font-bold tabular-nums">{{ $statsData['lunas'] }} <span class="text-slate-400 font-normal text-xs">({{ $lunasPct }}%)</span></span>
                </div>
                <div class="h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" style="width: {{ $lunasPct }}%"></div>
                </div>
            </div>
            {{-- Belum --}}
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-semibold text-rose-600 dark:text-rose-400">Belum Lunas</span>
                    <span class="text-sm font-bold tabular-nums">{{ $statsData['belum'] }} <span class="text-slate-400 font-normal text-xs">({{ $belumPct }}%)</span></span>
                </div>
                <div class="h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500 rounded-full transition-all duration-700" style="width: {{ $belumPct }}%"></div>
                </div>
            </div>

            {{-- Summary pill --}}
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <span class="text-xs text-slate-500">Total client</span>
                <span class="font-bold tabular-nums">{{ $totalPay }}</span>
            </div>
        </div>
    </div>

    {{-- Margin per Website Chart --}}
    <div class="lg:col-span-2 p-5 rounded-xl border shadow-sm bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            @include('components.icon', ['name' => 'bar-chart-2', 'class' => 'w-4 h-4 text-slate-400'])
            Margin per Website (Top {{ count($statsData['margins']) }})
        </h3>
        <div class="h-48">
            <canvas id="marginChart"></canvas>
        </div>
    </div>
</div>

@include('components.data-table')
@endsection

@push('scripts')
<script>
window.WHSection   = 'finansial';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));

// Margin chart
(function() {
    const isDark    = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(51,65,85,0.8)' : 'rgba(226,232,240,0.8)';
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const margins   = @json($statsData['margins']);

    // Hitung batas atas dinamis: ceil ke kelipatan 1 juta terdekat
    const maxMargin  = Math.max(...margins.map(m => m.margin), 0);
    const JT         = 1_000_000;
    const axisMax    = maxMargin <= 0 ? JT : Math.ceil(maxMargin / JT) * JT;

    // Format label sumbu X: "Rp X juta"
    const fmtJuta = (v) => {
        if (v === 0) return 'Rp 0';
        const juta = v / JT;
        // Tampilkan 1 desimal hanya bila bukan bilangan bulat
        const label = Number.isInteger(juta) ? juta : juta.toFixed(1).replace('.', ',');
        return 'Rp ' + label + ' juta';
    };

    new Chart(document.getElementById('marginChart'), {
        type: 'bar',
        data: {
            labels: margins.map(m => m.website),
            datasets: [{
                label: 'Margin',
                data: margins.map(m => m.margin),
                backgroundColor: margins.map(m =>
                    m.margin >= 0 ? 'rgba(16,185,129,0.75)' : 'rgba(239,68,68,0.75)'
                ),
                borderRadius: 5,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) } }
            },
            scales: {
                x: {
                    min: 0,
                    max: axisMax,
                    ticks: {
                        color: textColor,
                        font: { size: 10 },
                        callback: v => fmtJuta(v),
                    },
                    grid: { color: gridColor }
                },
                y: { ticks: { color: textColor, font: { size: 11 } }, grid: { display: false } },
            }
        }
    });
})();
</script>
@endpush
