{{--
    Data Table Component — reusable di semua section page
    Variables expected:
      $websites  — paginated collection
      $columns   — array of column config
      $section   — current section name (string)
      $search    — current search query
      $perPage   — current per_page value
      $dropdowns — DropdownConfig collection keyed by 'key'
--}}
<div class="rounded-xl shadow-sm border overflow-hidden bg-white dark:bg-slate-800 border-gray-100 dark:border-slate-700">

    {{-- Table Controls --}}
    <div class="p-4 border-b border-gray-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

        {{-- Per page selector --}}
        <div class="flex items-center gap-2">
            <span class="text-sm text-slate-500 dark:text-slate-400">Show</span>
            <form method="GET">
                <input type="hidden" name="search" value="{{ $search }}"/>
                <select
                    name="per_page"
                    onchange="this.form.submit()"
                    class="border rounded-lg px-2 py-1.5 text-sm outline-none transition
                           bg-white border-gray-300 text-slate-900
                           dark:bg-slate-700 dark:border-slate-600 dark:text-white
                           focus:ring-2 focus:ring-blue-500"
                >
                    @foreach ([10, 25, 50] as $n)
                    <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </form>
            <span class="text-sm text-slate-500 dark:text-slate-400">entries</span>
        </div>

        {{-- Search + Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

            {{-- Search Box --}}
            <form method="GET" class="relative flex-1 w-full sm:w-auto">
                <input type="hidden" name="per_page" value="{{ $perPage }}"/>
                <div class="relative">
                    @include('components.icon', ['name' => 'search', 'class' => 'w-4 h-4 absolute left-3 top-2.5 text-gray-400 pointer-events-none'])
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari client atau website..."
                        class="pl-9 pr-4 py-2 border rounded-lg text-sm w-full outline-none transition
                               bg-white border-gray-300 text-slate-900
                               dark:bg-slate-700 dark:border-slate-600 dark:text-white
                               focus:ring-2 focus:ring-blue-500"
                    />
                </div>
            </form>

            <div class="flex gap-2 w-full sm:w-auto">
                {{-- Edit Table (dropdown config) --}}
                <button
                    onclick="openModalEditTable()"
                    class="flex-1 sm:flex-none justify-center px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition border
                           border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200
                           dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600"
                >
                    @include('components.icon', ['name' => 'settings', 'class' => 'w-4 h-4'])
                    Edit Table
                </button>

                {{-- Tambah --}}
                <button
                    onclick="openModalAdd()"
                    class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-blue-700 transition shadow-sm"
                >
                    @include('components.icon', ['name' => 'plus', 'class' => 'w-4 h-4'])
                    Tambah
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-800 dark:text-slate-200">
            <thead class="uppercase text-xs font-semibold whitespace-nowrap bg-gray-50 text-gray-600 dark:bg-slate-700 dark:text-slate-300">
                <tr>
                    <th class="px-4 py-3">No</th>
                    @foreach ($columns as $col)
                    <th class="px-4 py-3">{{ $col['label'] }}</th>
                    @endforeach
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700 whitespace-nowrap">
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
                            {{-- View --}}
                            <button
                                onclick="openModalView({{ $website->id }})"
                                class="p-1.5 bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition"
                                title="Lihat Detail"
                            >
                                @include('components.icon', ['name' => 'eye', 'class' => 'w-4 h-4'])
                            </button>
                            {{-- Edit --}}
                            <button
                                onclick="openModalEdit({{ $website->id }})"
                                class="p-1.5 bg-amber-500/10 text-amber-500 rounded-lg hover:bg-amber-500/20 transition"
                                title="Edit Data"
                            >
                                @include('components.icon', ['name' => 'edit', 'class' => 'w-4 h-4'])
                            </button>
                            {{-- Delete --}}
                            <form
                                method="POST"
                                action="{{ route('websites.destroy', $website->id) }}"
                                onsubmit="return confirmDelete(event, this)"
                                class="inline"
                            >
                                @csrf @method('DELETE')
                                <button
                                    type="submit"
                                    class="p-1.5 bg-rose-500/10 text-rose-500 rounded-lg hover:bg-rose-500/20 transition"
                                    title="Hapus"
                                >
                                    @include('components.icon', ['name' => 'trash', 'class' => 'w-4 h-4'])
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($columns) + 2 }}" class="px-4 py-10 text-center text-slate-400 dark:text-slate-500">
                        Tidak ada data yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($websites->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-500 dark:text-slate-400">
        <div>
            Menampilkan {{ $websites->firstItem() }} – {{ $websites->lastItem() }} dari {{ $websites->total() }} entri
        </div>
        <div class="flex items-center gap-1">
            @if ($websites->onFirstPage())
                <span class="px-3 py-1.5 rounded border border-gray-200 dark:border-slate-600 text-slate-300 dark:text-slate-600 cursor-not-allowed">‹</span>
            @else
                <a href="{{ $websites->previousPageUrl() }}" class="px-3 py-1.5 rounded border border-gray-200 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition">‹</a>
            @endif

            @foreach ($websites->getUrlRange(max(1, $websites->currentPage()-2), min($websites->lastPage(), $websites->currentPage()+2)) as $page => $url)
                @if ($page == $websites->currentPage())
                    <span class="px-3 py-1.5 rounded bg-blue-600 text-white border border-blue-600">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1.5 rounded border border-gray-200 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition">{{ $page }}</a>
                @endif
            @endforeach

            @if ($websites->hasMorePages())
                <a href="{{ $websites->nextPageUrl() }}" class="px-3 py-1.5 rounded border border-gray-200 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-700 transition">›</a>
            @else
                <span class="px-3 py-1.5 rounded border border-gray-200 dark:border-slate-600 text-slate-300 dark:text-slate-600 cursor-not-allowed">›</span>
            @endif
        </div>
    </div>
    @endif
</div>
