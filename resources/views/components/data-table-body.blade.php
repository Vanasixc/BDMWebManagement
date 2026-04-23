<tbody id="table-tbody" class="divide-y divide-gray-100 dark:divide-slate-700 whitespace-nowrap">
    @forelse ($websites as $idx => $website)
    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
        <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $websites->firstItem() + $idx }}</td>

        @foreach ($columns as $col)
        <td class="px-4 py-3">
            @if (!empty($col['badge']))
            @include('components.status-badge', ['status' => $website->{$col['key']}])
            @elseif (!empty($col['pay_badge']))
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                {{ $website->{$col['key']} === 'Lunas'
                                   ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                   : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }}">
                {{ $website->{$col['key']} }}
            </span>
            @elseif (!empty($col['reminder_badge']))
            @include('components.reminder-badge', ['status' => $website->reminder_status])
            @elseif (!empty($col['days_col']))
            @if (!$website->hosting_exp_date)
            <span class="text-slate-400">-</span>
            @else
            @php $d = $website->days_remaining; @endphp
            <span class="font-bold tabular-nums {{ $d < 0 ? 'text-rose-600 dark:text-rose-400' : ($d <= 3 ? 'text-rose-500 dark:text-rose-400' : ($d <= 30 ? 'text-amber-500 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400')) }}">
                {{ $d < 0 ? 'Telat '.abs($d).'h' : $d.' hari' }}
            </span>
            @endif
            @elseif (!empty($col['currency']))
            @if (!empty($col['computed']) && $col['key'] === 'margin')
            <span class="font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">
                Rp {{ number_format($website->margin, 0, ',', '.') }}
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
            {{ $website->{$col['key']} ?? '-' }}
            @endif
        </td>
        @endforeach

        {{-- Actions --}}
        <td class="px-4 py-3">
            <div class="flex justify-center gap-2">

                <button
                    onclick="openModalView({{ $website->id }})"
                    class="p-1.5 bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition"
                    title="Lihat Detail">
                    @include('components.icon', ['name' => 'eye', 'class' => 'w-4 h-4'])
                </button>

                @if (auth()->user()->canModify())

                @if ($section !== 'reminder')
                <button
                    onclick="openModalEdit({{ $website->id }})"
                    class="p-1.5 bg-amber-500/10 text-amber-500 rounded-lg hover:bg-amber-500/20 transition"
                    title="Edit Data">
                    @include('components.icon', ['name' => 'edit', 'class' => 'w-4 h-4'])
                </button>
                @endif

                @if ($section === 'master')
                <form
                    method="POST"
                    action="{{ route('websites.destroy', $website->id) }}"
                    onsubmit="return confirmDelete(event, this)"
                    class="inline">
                    @csrf @method('DELETE')
                    <button
                        type="submit"
                        class="p-1.5 bg-rose-500/10 text-rose-500 rounded-lg hover:bg-rose-500/20 transition"
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
                        class="p-1.5 bg-rose-500/10 text-rose-500 rounded-lg hover:bg-rose-500/20 transition"
                        title="Reset Data Section">
                        @include('components.icon', ['name' => 'x-circle', 'class' => 'w-4 h-4'])
                    </button>
                </form>
                @endif

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