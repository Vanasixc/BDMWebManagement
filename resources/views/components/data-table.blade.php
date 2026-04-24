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
                <input type="hidden" name="search" value="{{ $search }}" />
                <select
                    name="per_page"
                    onchange="this.form.submit()"
                    class="border rounded-lg px-2 py-1.5 text-sm outline-none transition
                           bg-white border-gray-300 text-slate-900
                           dark:bg-slate-700 dark:border-slate-600 dark:text-white
                           focus:ring-2 focus:ring-blue-500">
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
                    class="flex-1 sm:flex-none justify-center px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition border
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
                    class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-blue-700 transition shadow-sm">
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
                    <th class="px-4 py-3">{{ $col['label'] }}</th>
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
    const input   = document.getElementById('search-input');
    const section = window.WHSection ?? 'master';
    let debounce;

    input.addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => {
            const search  = input.value;
            const perPage = {{ $perPage }};

            fetch(`/websites/search?search=${encodeURIComponent(search)}&per_page=${perPage}&section=${section}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('table-tbody').innerHTML = data.html;
                    document.getElementById('pagination-links').innerHTML = data.links ?? '';
                    const info = document.getElementById('pagination-info');
                    if (info) {
                        info.textContent = data.from && data.to
                            ? `Menampilkan ${data.from} – ${data.to} dari ${data.total} entri`
                            : '';
                    }
                });
        }, 350);
    });
})();
</script>
@endpush