@extends('layouts.app')
@section('title', 'Reminder')
@section('page_title', 'Reminder')
@section('page_subtitle', 'Pantau status expired domain & hosting website client')

@section('content')

{{-- ═══ BARIS 1: Monitoring Cards (Hosting kiri, Domain kanan) ═══ --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

    {{-- Monitoring Hosting --}}
    <div class="flex flex-col gap-4">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 flex items-center gap-2">
            @include('components.icon', ['name' => 'server', 'class' => 'w-3.5 h-3.5'])
            Monitoring Hosting
        </p>
        <div class="grid grid-cols-3 gap-3">
            <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                        bg-emerald-50 border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20">
                <div class="w-9 h-9 rounded-xl bg-emerald-500 shadow-lg shadow-emerald-500/30 flex items-center justify-center mb-1">
                    @include('components.icon', ['name' => 'shield-check', 'class' => 'w-5 h-5 text-white'])
                </div>
                <p class="text-2xl font-extrabold tabular-nums text-emerald-700 dark:text-emerald-400">{{ $statsData['aman'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-500">Aman</p>
                <p class="text-[9px] text-slate-400">> 30 hari</p>
            </div>
            <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                        bg-amber-50 border-amber-100 dark:bg-amber-500/10 dark:border-amber-500/20">
                <div class="w-9 h-9 rounded-xl bg-amber-500 shadow-lg shadow-amber-500/30 flex items-center justify-center mb-1">
                    @include('components.icon', ['name' => 'clock', 'class' => 'w-5 h-5 text-white'])
                </div>
                <p class="text-2xl font-extrabold tabular-nums text-amber-700 dark:text-amber-400">{{ $statsData['siaga'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-500">Siaga</p>
                <p class="text-[9px] text-slate-400">3–30 hari</p>
            </div>
            <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                        bg-rose-50 border-rose-100 dark:bg-rose-500/10 dark:border-rose-500/20">
                <div class="w-9 h-9 rounded-xl bg-rose-500 shadow-lg shadow-rose-500/30 flex items-center justify-center mb-1">
                    @include('components.icon', ['name' => 'alert-triangle', 'class' => 'w-5 h-5 text-white'])
                </div>
                <p class="text-2xl font-extrabold tabular-nums text-rose-700 dark:text-rose-400">{{ $statsData['darurat'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-500">Darurat</p>
                <p class="text-[9px] text-slate-400">< 3 hari / exp</p>
            </div>
        </div>
        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 text-xs text-slate-500 dark:text-slate-400 space-y-1">
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>Aman: sisa hosting > 30 hari</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>Siaga: sisa hosting 3–30 hari</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>Darurat: < 3 hari atau sudah expired</div>
        </div>
    </div>

    {{-- Monitoring Domain --}}
    <div class="flex flex-col gap-4">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 flex items-center gap-2">
            @include('components.icon', ['name' => 'globe', 'class' => 'w-3.5 h-3.5'])
            Monitoring Domain
        </p>
        <div class="grid grid-cols-3 gap-3">
            <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                        bg-emerald-50 border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20">
                <div class="w-9 h-9 rounded-xl bg-emerald-500 shadow-lg shadow-emerald-500/30 flex items-center justify-center mb-1">
                    @include('components.icon', ['name' => 'shield-check', 'class' => 'w-5 h-5 text-white'])
                </div>
                <p class="text-2xl font-extrabold tabular-nums text-emerald-700 dark:text-emerald-400">{{ $statsData['domain_aman'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-500">Aman</p>
                <p class="text-[9px] text-slate-400">> 30 hari</p>
            </div>
            <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                        bg-amber-50 border-amber-100 dark:bg-amber-500/10 dark:border-amber-500/20">
                <div class="w-9 h-9 rounded-xl bg-amber-500 shadow-lg shadow-amber-500/30 flex items-center justify-center mb-1">
                    @include('components.icon', ['name' => 'clock', 'class' => 'w-5 h-5 text-white'])
                </div>
                <p class="text-2xl font-extrabold tabular-nums text-amber-700 dark:text-amber-400">{{ $statsData['domain_siaga'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-500">Siaga</p>
                <p class="text-[9px] text-slate-400">3–30 hari</p>
            </div>
            <div class="p-4 rounded-xl border shadow-sm flex flex-col items-center justify-center gap-1 text-center
                        bg-rose-50 border-rose-100 dark:bg-rose-500/10 dark:border-rose-500/20">
                <div class="w-9 h-9 rounded-xl bg-rose-500 shadow-lg shadow-rose-500/30 flex items-center justify-center mb-1">
                    @include('components.icon', ['name' => 'alert-triangle', 'class' => 'w-5 h-5 text-white'])
                </div>
                <p class="text-2xl font-extrabold tabular-nums text-rose-700 dark:text-rose-400">{{ $statsData['domain_darurat'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-500">Darurat</p>
                <p class="text-[9px] text-slate-400">< 3 hari / exp</p>
            </div>
        </div>
        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 text-xs text-slate-500 dark:text-slate-400 space-y-1">
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>Aman: sisa domain > 30 hari</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>Siaga: sisa domain 3–30 hari</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>Darurat: < 3 hari atau sudah expired</div>
        </div>
    </div>
</div>

{{-- ═══ BARIS 2: Deadline Cards (Hosting kiri, Domain kanan) ═══ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Deadline Hosting --}}
    <div class="p-5 rounded-xl border shadow-sm bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-4 flex items-center gap-2">
            @include('components.icon', ['name' => 'calendar', 'class' => 'w-4 h-4 text-slate-400'])
            Deadline Hosting Terdekat
        </h3>
        @if(count($statsData['deadlines']) > 0)
        <div class="space-y-3">
            @foreach($statsData['deadlines'] as $dl)
            @php
                $d = $dl['days']; $s = $dl['status'];
                if ($s === 'Expired' || $s === 'Kritis') { $cl = ['bar' => 'bg-rose-500', 'text' => 'text-rose-600 dark:text-rose-400', 'badge' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400']; }
                elseif ($s === 'Segera')                 { $cl = ['bar' => 'bg-amber-500', 'text' => 'text-amber-600 dark:text-amber-400', 'badge' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400']; }
                else                                     { $cl = ['bar' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400', 'badge' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400']; }
                $barPct = $d <= 0 ? 0 : min(100, round($d / 365 * 100));
                $displayLabel = match($s) { 'Expired', 'Kritis' => 'Darurat', 'Segera' => 'Siaga', default => $s };
            @endphp
            <div class="flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold truncate" title="{{ $dl['website'] }}">{{ $dl['website'] }}</span>
                        <div class="flex items-center gap-2 shrink-0 ml-2">
                            <span class="text-xs {{ $cl['text'] }} font-bold tabular-nums">{{ $d < 0 ? 'Telat '.abs($d).'h' : $d.' hari' }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cl['badge'] }}">{{ $displayLabel }}</span>
                        </div>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full {{ $cl['bar'] }} rounded-full transition-all duration-700" style="width: {{ $barPct }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-0.5">Exp: {{ $dl['exp_date'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="h-32 flex items-center justify-center text-slate-400 dark:text-slate-500 text-sm">
            Belum ada data hosting dengan tanggal expired.
        </div>
        @endif
    </div>

    {{-- Deadline Domain --}}
    <div class="p-5 rounded-xl border shadow-sm bg-white border-gray-100 dark:bg-slate-800 dark:border-slate-700">
        <h3 class="font-bold text-sm md:text-base mb-4 flex items-center gap-2">
            @include('components.icon', ['name' => 'calendar', 'class' => 'w-4 h-4 text-slate-400'])
            Deadline Domain Terdekat
        </h3>
        @if(count($statsData['domain_deadlines']) > 0)
        <div class="space-y-3">
            @foreach($statsData['domain_deadlines'] as $dl)
            @php
                $d = $dl['days']; $s = $dl['status'];
                if ($s === 'Expired' || $s === 'Kritis') { $cl = ['bar' => 'bg-rose-500', 'text' => 'text-rose-600 dark:text-rose-400', 'badge' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400']; }
                elseif ($s === 'Segera')                 { $cl = ['bar' => 'bg-amber-500', 'text' => 'text-amber-600 dark:text-amber-400', 'badge' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400']; }
                else                                     { $cl = ['bar' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400', 'badge' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400']; }
                $barPct = $d <= 0 ? 0 : min(100, round($d / 365 * 100));
                $displayLabel = match($s) { 'Expired', 'Kritis' => 'Darurat', 'Segera' => 'Siaga', default => $s };
            @endphp
            <div class="flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold truncate" title="{{ $dl['website'] }}">{{ $dl['website'] }}</span>
                        <div class="flex items-center gap-2 shrink-0 ml-2">
                            <span class="text-xs {{ $cl['text'] }} font-bold tabular-nums">{{ $d < 0 ? 'Telat '.abs($d).'h' : $d.' hari' }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cl['badge'] }}">{{ $displayLabel }}</span>
                        </div>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full {{ $cl['bar'] }} rounded-full transition-all duration-700" style="width: {{ $barPct }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-0.5">Exp: {{ $dl['exp_date'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="h-32 flex items-center justify-center text-slate-400 dark:text-slate-500 text-sm">
            Belum ada data domain dengan tanggal expired.
        </div>
        @endif
    </div>
</div>

@include('components.data-table')
@endsection

@push('scripts')
<script>
window.WHSection   = 'reminder';
window.WHDropdowns = @json($dropdowns->map(fn($d) => $d->options));
</script>
@endpush
