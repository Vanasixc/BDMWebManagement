<div class="rounded-md shadow-sm border overflow-hidden bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">

    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

        <div class="flex items-center gap-2">
            <span class="text-sm text-slate-500 dark:text-slate-400">Show</span>
            <select
                id="per-page-select"
                class="border rounded px-2 py-1 text-sm outline-none transition cursor-pointer
                       bg-white border-gray-300 text-gray-700
                       dark:bg-gray-700 dark:border-gray-600 dark:text-white
                       focus:ring-2 focus:ring-purple-500">
                @foreach ([10, 25, 50] as $n)
                <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <span class="text-sm text-slate-500 dark:text-slate-400">entries</span>
        </div>


        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

            <div class="relative flex-1 w-full sm:w-auto">
                @include('components.icon', ['name' => 'search', 'class' => 'w-4 h-4 absolute left-3 top-2.5 text-gray-400 pointer-events-none'])
                <input
                    type="text"
                    id="search-input"
                    value="{{ $search }}"
                    placeholder="Cari client atau website..."
                    class="pl-9 pr-4 py-2 border rounded text-sm w-full outline-none transition
               bg-white border-gray-300 text-gray-800
               dark:bg-gray-700 dark:border-gray-600 dark:text-white
               focus:ring-2 focus:ring-purple-500" />
            </div>

            <div class="flex gap-2 w-full sm:w-auto items-center">
                @if (!in_array($section, ['reminder', 'finansial', 'domain']))
                <button
                    onclick="openModalEditTable()"
                    class="flex-1 sm:flex-none justify-center px-3 py-2 rounded text-sm flex items-center gap-2 transition border cursor-pointer
                           border-gray-300 bg-white text-gray-700 hover:bg-gray-50
                           dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    @include('components.icon', ['name' => 'settings', 'class' => 'w-4 h-4'])
                    Edit Table
                </button>
                @endif

                @stack('table-actions')

                @if ($section === 'master' && auth()->user()->canModify())
                <button
                    onclick="openModalAdd()"
                    class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-2 rounded text-sm flex items-center gap-2 hover:bg-blue-700 transition cursor-pointer">
                    @include('components.icon', ['name' => 'plus', 'class' => 'w-4 h-4'])
                    Tambah
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-800 dark:text-gray-200">
            <thead class="uppercase text-xs font-semibold whitespace-nowrap bg-gray-50 text-gray-500 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                <tr>
                    <th class="px-4 py-3">No</th>
                    @foreach ($columns as $col)
                    @php
                        // Kolom tidak bisa di-sort jika: computed DAN tidak ada flag sortable,
                        // atau reminder_badge, atau Domain URL di section domain
                        $noSort = (!empty($col['computed']) && empty($col['sortable']))
                               || !empty($col['reminder_badge'])
                               || ($col['key'] === 'url' && $section === 'domain');
                        $dbKey    = $col['key'];
                        $isActive = $sortBy === $dbKey;
                        $nextDir  = ($isActive && $sortDir === 'asc') ? 'desc' : 'asc';
                        $sortIcon = $isActive
                            ? ($sortDir === 'asc' ? '↑' : '↓')
                            : '↕';
                    @endphp
                    @if ($noSort)
                    <th class="px-4 py-3">{{ $col['label'] }}</th>
                    @else
                    <th class="px-4 py-3">
                        <button
                            type="button"
                            data-sort="{{ $dbKey }}"
                            data-dir="{{ $nextDir }}"
                            class="sort-btn inline-flex items-center gap-1 hover:text-blue-500 dark:hover:text-blue-400 transition group cursor-pointer {{ $isActive ? 'text-blue-600 dark:text-blue-400' : '' }}">
                            {{ $col['label'] }}
                            <span class="text-[10px] {{ $isActive ? 'opacity-100' : 'opacity-30 group-hover:opacity-70' }}">{{ $sortIcon }}</span>
                        </button>
                    </th>
                    @endif
                    @endforeach
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            @include('components.data-table-body')
        </table>
    </div>

    {{-- Pagination --}}
    <div id="pagination-section"
         class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-gray-500 dark:text-gray-400"
         @if (!$websites->hasPages()) style="display:none" @endif>
        <div id="pagination-info">
            @if ($websites->hasPages())
            Menampilkan {{ $websites->firstItem() }} &ndash; {{ $websites->lastItem() }} dari {{ $websites->total() }} entri
            @endif
        </div>
        <div id="pagination-links" class="flex items-center gap-1">
            @if ($websites->hasPages())

            {{-- First Page --}}
            @if ($websites->onFirstPage())
            <span class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-300 dark:text-gray-600 cursor-not-allowed">&laquo;</span>
            @else
            <button type="button" data-page="1"
                    class="page-btn px-3 py-1 rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer">&laquo;</button>
            @endif

            {{-- Prev --}}
            @if ($websites->onFirstPage())
            <span class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-300 dark:text-gray-600 cursor-not-allowed">&lsaquo;</span>
            @else
            <button type="button" data-page="{{ $websites->currentPage() - 1 }}"
                    class="page-btn px-3 py-1 rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer">&lsaquo;</button>
            @endif

            {{-- Halaman ± 2 --}}
            @foreach (range(max(1, $websites->currentPage()-2), min($websites->lastPage(), $websites->currentPage()+2)) as $page)
            @if ($page == $websites->currentPage())
            <span class="px-3 py-1 rounded bg-blue-600 text-white border border-blue-600">{{ $page }}</span>
            @else
            <button type="button" data-page="{{ $page }}"
                    class="page-btn px-3 py-1 rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer">{{ $page }}</button>
            @endif
            @endforeach

            {{-- Next --}}
            @if ($websites->hasMorePages())
            <button type="button" data-page="{{ $websites->currentPage() + 1 }}"
                    class="page-btn px-3 py-1 rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer">&rsaquo;</button>
            @else
            <span class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-300 dark:text-gray-600 cursor-not-allowed">&rsaquo;</span>
            @endif

            {{-- Last Page --}}
            @if ($websites->hasMorePages())
            <button type="button" data-page="{{ $websites->lastPage() }}"
                    class="page-btn px-3 py-1 rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer">&raquo;</button>
            @else
            <span class="px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-300 dark:text-gray-600 cursor-not-allowed">&raquo;</span>
            @endif

            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
if (!window.WHSection) {
    window.WHSection = '{{ $section }}';
}
(function () {
    const input    = document.getElementById('search-input');
    const section  = window.WHSection ?? 'master';
    let debounce;
    let currentSortBy  = '{{ $sortBy }}';
    let currentSortDir = '{{ $sortDir }}';
    let currentPerPage = {{ $perPage }};

    // Rebuild pagination
    function buildPagination(data) {
        const { from, to, total, currentPage, lastPage } = data;

        // Update info text
        const info = document.getElementById('pagination-info');
        if (info) {
            info.textContent = from && to
                ? `Menampilkan ${from} \u2013 ${to} dari ${total} entri`
                : '';
        }

        const paginLinks = document.getElementById('pagination-links');
        if (!paginLinks) return;

        // Hide section pagination 
        const paginSection = document.getElementById('pagination-section');
        if (paginSection) {
            paginSection.style.display = lastPage <= 1 ? 'none' : '';
        }

        if (lastPage <= 1) return;

        const cls = {
            inactive: 'px-3 py-1 rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer',
            active:   'px-3 py-1 rounded bg-blue-600 text-white border border-blue-600',
            disabled: 'px-3 py-1 rounded border border-gray-300 dark:border-gray-600 text-gray-300 dark:text-gray-600 cursor-not-allowed',
        };

        let html = '';

        // First page
        if (currentPage <= 1) {
            html += `<span class="${cls.disabled}">&laquo;</span>`;
        } else {
            html += `<button type="button" data-page="1" class="page-btn ${cls.inactive}">&laquo;</button>`;
        }

        // prev
        if (currentPage <= 1) {
            html += `<span class="${cls.disabled}">&lsaquo;</span>`;
        } else {
            html += `<button type="button" data-page="${currentPage - 1}" class="page-btn ${cls.inactive}">&lsaquo;</button>`;
        }

        // Range halaman
        const rangeStart = Math.max(1, currentPage - 2);
        const rangeEnd   = Math.min(lastPage, currentPage + 2);
        for (let p = rangeStart; p <= rangeEnd; p++) {
            if (p === currentPage) {
                html += `<span class="${cls.active}">${p}</span>`;
            } else {
                html += `<button type="button" data-page="${p}" class="page-btn ${cls.inactive}">${p}</button>`;
            }
        }

        // next
        if (currentPage >= lastPage) {
            html += `<span class="${cls.disabled}">&rsaquo;</span>`;
        } else {
            html += `<button type="button" data-page="${currentPage + 1}" class="page-btn ${cls.inactive}">&rsaquo;</button>`;
        }

        // Last page
        if (currentPage >= lastPage) {
            html += `<span class="${cls.disabled}">&raquo;</span>`;
        } else {
            html += `<button type="button" data-page="${lastPage}" class="page-btn ${cls.inactive}">&raquo;</button>`;
        }

        paginLinks.innerHTML = html;

        // Bind tombol halaman
        paginLinks.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                fetchTable({ page: parseInt(this.dataset.page) });
            });
        });
    }

    function fetchTable({ search, perPage, sortBy, sortDir, page } = {}) {
        search   = search   ?? input.value;
        perPage  = perPage  ?? currentPerPage;
        sortBy   = sortBy   ?? currentSortBy;
        sortDir  = sortDir  ?? currentSortDir;
        page     = page     ?? 1;
        currentPerPage = perPage;

        const params = new URLSearchParams({
            search, per_page: perPage, section, page,
            sort_by: sortBy, sort_dir: sortDir,
        });

        fetch(`/websites/search?${params}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('table-tbody').innerHTML = data.html;

                buildPagination(data);

                if (data.sortBy  !== undefined) currentSortBy  = data.sortBy;
                if (data.sortDir !== undefined) currentSortDir = data.sortDir;

                document.querySelectorAll('.sort-btn').forEach(btn => {
                    const key = btn.dataset.sort;
                    const isActive = key === currentSortBy;
                    const icon = btn.querySelector('span');
                    btn.classList.toggle('text-blue-600', isActive);
                    btn.classList.toggle('dark:text-blue-400', isActive);
                    if (icon) {
                        icon.textContent = isActive
                            ? (currentSortDir === 'asc' ? '↑' : '↓')
                            : '↕';
                        icon.classList.toggle('opacity-100', isActive);
                        icon.classList.toggle('opacity-30',  !isActive);
                    }
                    btn.dataset.dir = isActive
                        ? (currentSortDir === 'asc' ? 'desc' : 'asc')
                        : 'asc';
                });
            });
    }

    input.addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => fetchTable({ page: 1 }), 350);
    });

    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const sortBy  = this.dataset.sort;
            const sortDir = this.dataset.dir;
            currentSortBy  = sortBy;
            currentSortDir = sortDir;
            fetchTable({ sortBy, sortDir, page: 1 });
        });
    });

    document.querySelectorAll('#pagination-links .page-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            fetchTable({ page: parseInt(this.dataset.page) });
        });
    });

    const perPageSel = document.getElementById('per-page-select');
    if (perPageSel) {
        perPageSel.addEventListener('change', function () {
            fetchTable({ perPage: parseInt(this.value), page: 1 });
        });
    }
})();
</script>
@endpush