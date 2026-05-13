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

        {{-- Per page selector (AJAX — tidak mengubah URL) --}}
        <div class="flex items-center gap-2">
            <span class="text-sm text-slate-500 dark:text-slate-400">Show</span>
            <select
                id="per-page-select"
                class="border rounded-lg px-2 py-1.5 text-sm outline-none transition cursor-pointer
                       bg-white border-gray-300 text-slate-900
                       dark:bg-slate-700 dark:border-slate-600 dark:text-white
                       focus:ring-2 focus:ring-blue-500">
                @foreach ([10, 25, 50] as $n)
                <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <span class="text-sm text-slate-500 dark:text-slate-400">entries</span>
        </div>


        {{-- Search + Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

            {{-- Search Box --}}
            <div class="relative flex-1 w-full sm:w-auto">
                @include('components.icon', ['name' => 'search', 'class' => 'w-4 h-4 absolute left-3 top-2.5 text-gray-400 pointer-events-none'])
                <input
                    type="text"
                    id="search-input"
                    value="{{ $search }}"
                    placeholder="Cari client atau website..."
                    class="pl-9 pr-4 py-2 border rounded-lg text-sm w-full outline-none transition
               bg-white border-gray-300 text-slate-900
               dark:bg-slate-700 dark:border-slate-600 dark:text-white
               focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="flex gap-2 w-full sm:w-auto items-center">
                {{-- Edit Table (dropdown config) --}}
                <button
                    onclick="openModalEditTable()"
                    class="flex-1 sm:flex-none justify-center px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition border cursor-pointer
                           border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200
                           dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
                    @include('components.icon', ['name' => 'settings', 'class' => 'w-4 h-4'])
                    Edit Table
                </button>

                {{-- Extra action buttons injected by individual pages --}}
                @stack('table-actions')

                @if ($section === 'master' && auth()->user()->canModify())
                <button
                    onclick="openModalAdd()"
                    class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-blue-700 transition shadow-sm cursor-pointer">
                    @include('components.icon', ['name' => 'plus', 'class' => 'w-4 h-4'])
                    Tambah
                </button>
                @endif
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
    @if ($websites->hasPages())
    <div class="p-4 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-500 dark:text-slate-400">
        <div id="pagination-info">
            Menampilkan {{ $websites->firstItem() }} – {{ $websites->lastItem() }} dari {{ $websites->total() }} entri
        </div>
        <div id="pagination-links" class="flex items-center gap-1">
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

@push('scripts')
<script>
if (!window.WHSection) {
    window.WHSection = '{{ $section }}';
}
(function () {
    const input    = document.getElementById('search-input');
    const section  = window.WHSection ?? 'master';
    let debounce;
    // State sort aktif — dibaca dari PHP saat halaman pertama load
    let currentSortBy  = '{{ $sortBy }}';
    let currentSortDir = '{{ $sortDir }}';
    let currentPerPage = {{ $perPage }};

    // Fungsi AJAX utama
    function fetchTable({ search, perPage, sortBy, sortDir, page } = {}) {
        search   = search   ?? input.value;
        perPage  = perPage  ?? currentPerPage;
        sortBy   = sortBy   ?? currentSortBy;
        sortDir  = sortDir  ?? currentSortDir;
        page     = page     ?? 1;
        // Simpan perPage yang digunakan agar konsisten di request berikutnya
        currentPerPage = perPage;

        const params = new URLSearchParams({
            search, per_page: perPage, section, page,
            sort_by: sortBy, sort_dir: sortDir,
        });

        fetch(`/websites/search?${params}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('table-tbody').innerHTML = data.html;

                // Update pagination
                const paginLinks = document.getElementById('pagination-links');
                if (paginLinks) paginLinks.innerHTML = data.links ?? '';
                const info = document.getElementById('pagination-info');
                if (info) {
                    info.textContent = data.from && data.to
                        ? `Menampilkan ${data.from} – ${data.to} dari ${data.total} entri`
                        : '';
                }

                // Update sort state dari respons
                if (data.sortBy  !== undefined) currentSortBy  = data.sortBy;
                if (data.sortDir !== undefined) currentSortDir = data.sortDir;

                // Update sort button visual state di header
                document.querySelectorAll('.sort-btn').forEach(btn => {
                    const key = btn.dataset.sort;
                    const isActive = key === currentSortBy;
                    const icon = btn.querySelector('span');
                    // Toggle warna aktif
                    btn.classList.toggle('text-blue-600', isActive);
                    btn.classList.toggle('dark:text-blue-400', isActive);
                    if (icon) {
                        icon.textContent = isActive
                            ? (currentSortDir === 'asc' ? '↑' : '↓')
                            : '↕';
                        icon.classList.toggle('opacity-100', isActive);
                        icon.classList.toggle('opacity-30',  !isActive);
                    }
                    // Update data-dir untuk klik berikutnya
                    btn.dataset.dir = isActive
                        ? (currentSortDir === 'asc' ? 'desc' : 'asc')
                        : 'asc';
                });

                // Re-bind pagination links agar tetap AJAX
                bindPaginationLinks();
            });
    }

    // Bind klik pada pagination links → AJAX
    function bindPaginationLinks() {
        document.querySelectorAll('#pagination-links a').forEach(a => {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                const url  = new URL(this.href);
                const page = url.searchParams.get('page') ?? 1;
                fetchTable({ page });
            });
        });
    }

    // Search input dengan debounce
    input.addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => fetchTable({ page: 1 }), 350);
    });

    // Klik sort button → AJAX tanpa refresh
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const sortBy  = this.dataset.sort;
            const sortDir = this.dataset.dir;
            currentSortBy  = sortBy;
            currentSortDir = sortDir;
            fetchTable({ sortBy, sortDir, page: 1 });
        });
    });

    // Bind pagination links saat load pertama
    bindPaginationLinks();

    // Per-page selector → AJAX (tidak reload URL)
    const perPageSel = document.getElementById('per-page-select');
    if (perPageSel) {
        perPageSel.addEventListener('change', function () {
            fetchTable({ perPage: parseInt(this.value), page: 1 });
        });
    }
})();
</script>
@endpush