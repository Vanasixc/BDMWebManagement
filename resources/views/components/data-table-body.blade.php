<tbody id="table-tbody" class="divide-y divide-gray-100 dark:divide-slate-700 whitespace-nowrap">
    @forelse ($websites as $idx => $website)
    @php
    $rowClass = 'hover:bg-gray-50 dark:hover:bg-slate-700/50';
    if ($section !== 'master') {
        if ($website->isAllEmpty($section)) {
            $rowClass = 'bg-rose-50/80 hover:bg-rose-100 dark:bg-rose-500/10 dark:hover:bg-rose-500/20';
        } elseif ($section === 'finansial' && $website->margin < 0) {
            // Rugi — highlight merah muda lebih soft agar berbeda dengan isAllEmpty
            $rowClass = 'bg-rose-50/60 hover:bg-rose-100/80 dark:bg-rose-500/5 dark:hover:bg-rose-500/15 border-l-2 border-l-rose-400';
        } elseif ($website->isIncomplete($section)) {
            $rowClass = 'bg-amber-50/80 hover:bg-amber-100 dark:bg-amber-500/10 dark:hover:bg-amber-500/20';
        }
    }
    @endphp
    <tr class="transition {{ $rowClass }}">
        <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $websites->firstItem() + $idx }}</td>

        @foreach ($columns as $col)
        <td class="px-4 py-3">
            @if (!empty($col['prioritas_badge']))
            @php
                $p = $website->{$col['key']} ?? 'Normal';
                $pColor = match($p) {
                    'VIP'      => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-500/40',
                    'Ekonomis' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-500/40',
                    default    => 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400 border border-slate-200 dark:border-slate-600',
                };
            @endphp
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $pColor }}">{{ $p }}</span>
            @elseif (!empty($col['badge']))
            @include('components.status-badge', ['status' => $website->{$col['key']}])
            @elseif (!empty($col['profit_badge']))
            @php $margin = $website->margin; @endphp
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                {{ $margin >= 0
                   ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                   : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }}">
                {{ $margin >= 0 ? 'Untung' : 'Rugi' }}
            </span>
            @elseif (!empty($col['pay_badge']))
            @php $payVal = $website->{$col['key']} ?? null; @endphp
            @if ($payVal)
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $payVal === 'Lunas'
                                   ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                   : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }}">
                {{ $payVal }}
            </span>
            @else
            <span class="text-slate-400">-</span>
            @endif
            @elseif (!empty($col['domain_days_col']))
            @if (!$website->domain_exp_date)
            <span class="text-slate-400">-</span>
            @else
            @php $dd = $website->domain_days_remaining; @endphp
            <span class="font-bold tabular-nums {{ $dd < 0 ? 'text-rose-600 dark:text-rose-400' : ($dd < 3 ? 'text-rose-500 dark:text-rose-400' : ($dd < 30 ? 'text-amber-500 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400')) }}">
                {{ $dd < 0 ? 'Telat '.abs($dd).'h' : $dd.' hari' }}
            </span>
            @endif
            @elseif (!empty($col['reminder_badge']))
            @include('components.reminder-badge', ['status' => $website->reminder_status])
            @elseif (!empty($col['days_col']))
            @if (!$website->hosting_exp_date)
            <span class="text-slate-400">-</span>
            @else
            @php $d = $website->days_remaining; @endphp
            <span class="font-bold tabular-nums {{ $d < 0 ? 'text-rose-600 dark:text-rose-400' : ($d < 3 ? 'text-rose-500 dark:text-rose-400' : ($d < 30 ? 'text-amber-500 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400')) }}">
                {{ $d < 0 ? 'Telat '.abs($d).'h' : $d.' hari' }}
            </span>
            @endif
            @elseif (!empty($col['currency']))
            @if (!empty($col['margin_col']))
            @php
                $marginVal = $website->margin;
                $isMonthly = ($website->pay_system ?? 'Tahunan') === 'Bulanan';
                $displayMargin = $isMonthly ? (int) round($marginVal / 12) : $marginVal;
                $marginSuffix  = $isMonthly ? '/bln' : '/thn';
            @endphp
            <span class="font-bold {{ $displayMargin >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} tabular-nums">
                Rp {{ number_format($displayMargin, 0, ',', '.') }}
                <span class="text-[10px] font-normal text-slate-400">{{ $marginSuffix }}</span>
            </span>
            @else
            <span class="tabular-nums">
                Rp {{ number_format($website->{$col['key']}, 0, ',', '.') }}
            </span>
            @endif
            @elseif (!empty($col['date']))
            {{ $website->{$col['key']} ? $website->{$col['key']}->format('d/m/Y') : '-' }}
            @elseif (!empty($col['suffix']))
            {{ $website->{$col['key']} }}{{ $col['suffix'] }}
            @else
            @if ($col['key'] === 'admin_url' && !empty($website->admin_url))
            <a href="{{ $website->admin_url }}" target="_blank" rel="noopener noreferrer"
                class="text-blue-500 hover:text-blue-700 hover:underline transition truncate max-w-[200px] block">
                {{ $website->admin_url }}
            </a>
            @elseif ($col['key'] === 'url' && !empty($website->url))
            <a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer"
                class="text-blue-500 hover:text-blue-700 hover:underline transition truncate max-w-[220px] block">
                {{ $website->url }}
            </a>
            @else
            {{ $website->{$col['key']} ?? '-' }}
            @endif
            @endif
        </td>
        @endforeach

        {{-- Actions --}}
        <td class="px-4 py-3">
            <div class="flex justify-center gap-2">

                <button
                    onclick="openModalView({{ $website->id }})"
                    class="p-1.5 bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition cursor-pointer"
                    title="Lihat Detail">
                    @include('components.icon', ['name' => 'info', 'class' => 'w-4 h-4'])
                </button>

                @if (auth()->user()->canModify())

                @if ($section === 'reminder')
                {{-- Reminder: Edit note + Reset note --}}
                <button
                    onclick="openModalEdit({{ $website->id }})"
                    class="p-1.5 bg-amber-500/10 text-amber-500 rounded-lg hover:bg-amber-500/20 transition cursor-pointer"
                    title="Edit Catatan">
                    @include('components.icon', ['name' => 'edit', 'class' => 'w-4 h-4'])
                </button>
                <form
                    method="POST"
                    action="{{ route('websites.clear', $website->id) }}"
                    onsubmit="return confirmClear(event, this)"
                    class="inline">
                    @csrf @method('PATCH')
                    <input type="hidden" name="section" value="reminder">
                    <button
                        type="submit"
                        class="p-1.5 bg-rose-500/10 text-rose-500 rounded-lg hover:bg-rose-500/20 transition cursor-pointer"
                        title="Reset Catatan">
                        @include('components.icon', ['name' => 'trash', 'class' => 'w-4 h-4'])
                    </button>
                </form>
                @else
                <button
                    onclick="openModalEdit({{ $website->id }})"
                    class="p-1.5 bg-amber-500/10 text-amber-500 rounded-lg hover:bg-amber-500/20 transition cursor-pointer"
                    title="Edit Data">
                    @include('components.icon', ['name' => 'edit', 'class' => 'w-4 h-4'])
                </button>

                @if ($section === 'master')
                <form
                    method="POST"
                    action="{{ route('websites.destroy', $website->id) }}"
                    onsubmit="return confirmDelete(event, this)"
                    class="inline">
                    @csrf @method('DELETE')
                    <button
                        type="submit"
                        class="p-1.5 bg-rose-500/10 text-rose-500 rounded-lg hover:bg-rose-500/20 transition cursor-pointer"
                        title="Hapus Permanen">
                        @include('components.icon', ['name' => 'trash', 'class' => 'w-4 h-4'])
                    </button>
                </form>

                @elseif (in_array($section, ['domain', 'hosting', 'akses', 'finansial']))
                <form
                    method="POST"
                    action="{{ route('websites.clear', $website->id) }}"
                    onsubmit="return confirmClear(event, this)"
                    class="inline">
                    @csrf @method('PATCH')
                    <input type="hidden" name="section" value="{{ $section }}">
                    <button
                        type="submit"
                        class="p-1.5 bg-rose-500/10 text-rose-500 rounded-lg hover:bg-rose-500/20 transition cursor-pointer"
                        title="Reset Data Section">
                        @include('components.icon', ['name' => 'trash', 'class' => 'w-4 h-4'])
                    </button>
                </form>
                @endif
                @endif {{-- end reminder/else --}}

                @endif {{-- end canModify --}}

            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="{{ count($columns) + 2 }}" class="px-4 py-10 text-center text-slate-400 dark:text-slate-500">
            Tidak ada data yang ditemukan
        </td>
    </tr>
    @endforelse
</tbody>