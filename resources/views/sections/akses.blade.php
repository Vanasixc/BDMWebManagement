@extends('layouts.app')
@section('title', 'Akses')
@section('page_title', 'Akses')
@section('page_subtitle', 'Kelola data akses admin & password seluruh website.')

@section('content')

{{-- ============================================================
     VISUALISASI: Kelengkapan Data Akses
     ============================================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @php
        $total = $statsData['total'];
        $accessItems = [
            [
                'label'  => 'URL Admin Tercatat',
                'count'  => $statsData['has_admin_url'],
                'icon'   => 'link',
                'color'  => 'blue',
                'bg'     => 'bg-blue-50 border-blue-100 dark:bg-blue-500/10 dark:border-blue-500/20',
                'icon_bg'=> 'bg-blue-500 shadow-blue-500/30',
                'text'   => 'text-blue-700 dark:text-blue-400',
                'bar'    => 'bg-blue-500',
            ],
            [
                'label'  => 'Extra Akses Tersedia',
                'count'  => $statsData['has_extra_access'],
                'icon'   => 'key',
                'color'  => 'violet',
                'bg'     => 'bg-violet-50 border-violet-100 dark:bg-violet-500/10 dark:border-violet-500/20',
                'icon_bg'=> 'bg-violet-500 shadow-violet-500/30',
                'text'   => 'text-violet-700 dark:text-violet-400',
                'bar'    => 'bg-violet-500',
            ],
            [
                'label'  => 'Password Tersimpan',
                'count'  => $statsData['has_password_loc'],
                'icon'   => 'lock',
                'color'  => 'emerald',
                'bg'     => 'bg-emerald-50 border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20',
                'icon_bg'=> 'bg-emerald-500 shadow-emerald-500/30',
                'text'   => 'text-emerald-700 dark:text-emerald-400',
                'bar'    => 'bg-emerald-500',
            ],
        ];
    @endphp

    @foreach($accessItems as $item)
    @php $pct = $total > 0 ? round($item['count'] / $total * 100) : 0; @endphp
    <div class="p-5 rounded-xl border shadow-sm {{ $item['bg'] }}">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-wider {{ $item['text'] }}">{{ $item['label'] }}</p>
            <div class="w-8 h-8 rounded-lg {{ $item['icon_bg'] }} shadow-lg flex items-center justify-center">
                @include('components.icon', ['name' => $item['icon'], 'class' => 'w-4 h-4 text-white'])
            </div>
        </div>
        <p class="text-3xl font-extrabold tabular-nums {{ $item['text'] }}">{{ $item['count'] }}<span class="text-sm font-medium text-slate-400 dark:text-slate-500">/{{ $total }}</span></p>
        <div class="mt-3 space-y-1">
            <div class="h-1.5 bg-black/10 dark:bg-white/10 rounded-full overflow-hidden">
                <div class="h-full rounded-full {{ $item['bar'] }} transition-all duration-700"
                     style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-xs {{ $item['text'] }}">{{ $pct }}% website telah terdata</p>
        </div>
    </div>
    @endforeach
</div>

@include('components.data-table')
@endsection

@push('scripts')
<script>
window.WHSection   = 'akses';
window.WHDropdowns = {};
</script>
@endpush
