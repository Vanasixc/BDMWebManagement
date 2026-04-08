{{--
    Reminder Badge Component
    Usage: @include('components.reminder-badge', ['status' => $website->reminder_status])
--}}
@php
    $colors = [
        'Aman'    => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
        'Segera'  => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
        'Kritis'  => 'bg-rose-500/10 text-rose-600 dark:text-rose-400',
        'Expired' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400',
    ];
    $color = $colors[$status] ?? 'bg-slate-500/10 text-slate-600';
@endphp
<span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color }}">
    {{ $status }}
</span>
