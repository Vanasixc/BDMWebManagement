<?php

namespace App\Http\Controllers;

use App\Models\DropdownConfig;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{
    /** Mengembalikan user yang sedang login dengan tipe konkret User (bukan Authenticatable). */
    private function authUser(): User
    {
        /** @var User $u */
        $u = Auth::user();
        return $u;
    }

    private array $sectionColumns = [
        'master' => [
            ['key' => 'client',       'label' => 'Nama Client'],
            ['key' => 'pic',          'label' => 'PIC'],
            ['key' => 'website',      'label' => 'Nama Website'],
            ['key' => 'type',         'label' => 'Jenis Website'],
            ['key' => 'prioritas',    'label' => 'Prioritas',    'prioritas_badge' => true],
            ['key' => 'status',       'label' => 'Status',       'badge' => true],
            ['key' => 'internal_pic', 'label' => 'PIC Internal'],
        ],
        'domain' => [
            ['key' => 'client',          'label' => 'Client'],
            ['key' => 'url',             'label' => 'Domain'],
            ['key' => 'domain_provider', 'label' => 'Provider'],
            ['key' => 'domain_reg_date', 'label' => 'Tgl Reg',     'date' => true],
            ['key' => 'domain_exp_date', 'label' => 'Tgl Expired', 'date' => true],
            ['key' => 'domain_price',    'label' => 'Harga/Thn',   'currency' => true],
        ],
        'hosting' => [
            ['key' => 'website',          'label' => 'Website'],
            ['key' => 'hosting_type',     'label' => 'Jenis Hosting'],
            ['key' => 'hosting_provider', 'label' => 'Provider'],
            ['key' => 'storage',          'label' => 'Storage',     'suffix' => ' GB'],
            ['key' => 'location',         'label' => 'Lokasi'],
            ['key' => 'hosting_exp_date', 'label' => 'Tgl Expired', 'date' => true],
        ],
        'akses' => [
            ['key' => 'admin_url',    'label' => 'URL Admin'],
            ['key' => 'extra_access', 'label' => 'Akses Tambahan'],
            ['key' => 'password_loc', 'label' => 'Simpan Pwd Di'],
            ['key' => 'note',         'label' => 'Catatan'],
        ],
        'finansial' => [
            ['key' => 'client',        'label' => 'Client'],
            ['key' => 'sell_price',    'label' => 'Harga Jual',   'currency' => true],
            ['key' => 'domain_price',  'label' => 'B. Domain',    'currency' => true],
            ['key' => 'hosting_price', 'label' => 'B. Hosting',   'currency' => true],
            ['key' => 'margin',        'label' => 'Margin',       'currency' => true, 'computed' => true, 'sortable' => true, 'highlight' => 'emerald'],
            ['key' => 'profit_status', 'label' => 'Status',       'profit_badge' => true, 'computed' => true],
        ],
        'reminder' => [
            ['key' => 'website',              'label' => 'Website'],
            ['key' => 'domain_exp_date',      'label' => 'Exp Domain',        'date' => true],
            ['key' => 'hosting_exp_date',     'label' => 'Exp Hosting',       'date' => true],
            ['key' => 'domain_days_remaining','label' => 'Sisa Hari Domain',  'computed' => true, 'sortable' => true, 'domain_days_col' => true],
            ['key' => 'days_remaining',       'label' => 'Sisa Hari Hosting', 'computed' => true, 'sortable' => true, 'days_col' => true],
            ['key' => 'reminder_status',      'label' => 'Status',            'reminder_badge' => true, 'computed' => true],
        ],
    ];

    public function index(Request $request, string $section = 'master')
    {
        $search  = $request->get('search', '');
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;
        $sortBy  = $request->get('sort_by') ?? '';
        $sortDir = ($request->get('sort_dir') ?? 'asc') === 'desc' ? 'desc' : 'asc';

        $query = Website::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(client) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(website) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
        $this->applySorting($query, $sortBy, $sortDir);

        $websites    = $query->paginate($perPage)->withQueryString();
        $columns     = $this->sectionColumns[$section] ?? $this->sectionColumns['master'];
        $dropdowns   = DropdownConfig::forPage($section);
        $allWebsites = Website::all();
        $statsData   = $this->buildStatsData($section, $allWebsites);

        return view("sections.{$section}", compact('websites', 'columns', 'section', 'search', 'perPage', 'dropdowns', 'allWebsites', 'statsData', 'sortBy', 'sortDir'));
    }

    private function applySorting($query, ?string $sortBy, ?string $sortDir): void
    {
        $sortBy = $sortBy ?? '';
        $dir    = ($sortDir ?? 'asc') === 'desc' ? 'desc' : 'asc';

        $directCols = [
            'client','website','url','status','type','technology','internal_pic',
            'domain_provider','domain_reg_date','domain_exp_date','domain_price',
            'hosting_type','hosting_provider','storage','location','hosting_exp_date','hosting_price',
            'admin_url','extra_access','password_loc','note',
            'sell_price','pay_status','invoice_date','pic','phone','email',
        ];

        match (true) {
            $sortBy === 'margin'               => $query->orderByRaw("(COALESCE(sell_price,0) - COALESCE(domain_price,0) - COALESCE(hosting_price,0)) {$dir}"),
            $sortBy === 'days_remaining'        => $query->orderBy('hosting_exp_date', $dir),
            $sortBy === 'domain_days_remaining' => $query->orderBy('domain_exp_date', $dir),
            in_array($sortBy, $directCols)     => $query->orderBy($sortBy, $dir),
            default                            => $query->orderBy('id', 'asc'),
        };
    }

    public function store(Request $request)
    {
        if (! $this->authUser()->canModify()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah data.');
        }

        $section = $request->input('section', 'master');
        $validated = $request->validate($this->validationRules($section));
        Website::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Data website berhasil ditambahkan!']);
        }
        return back()->with('success', 'Data website berhasil ditambahkan!');
    }

    public function update(Request $request, Website $website)
    {
        if (! $this->authUser()->canModify()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data.');
        }

        $section = $request->get('section', 'master');
        $validated = $request->validate($this->validationRules($section));
        $website->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Data website berhasil diperbarui!']);
        }
        return back()->with('success', 'Data website berhasil diperbarui!');
    }

    public function destroy(Website $website)
    {
        if (! $this->authUser()->canModify()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        Website::destroy($website->id);
        return back()->with('success', 'Data website berhasil dihapus!');
    }

    public function clear(Request $request, Website $website)
    {
        $user = $this->authUser();

        if ($user->isUser()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data');
        }

        $section = $request->input('section');

        $sectionFields = [
            'domain'   => [
                'domain_provider' => null,
                'domain_email'    => null,
                'domain_reg_date' => null,
                'domain_exp_date' => null,
                'domain_price'    => 0,
            ],
            'hosting'  => [
                'hosting_type'     => null,
                'hosting_provider' => null,
                'storage'          => 0,
                'ip_server'        => null,
                'location'         => null,
                'hosting_email'    => null,
                'hosting_exp_date' => null,
                'hosting_price'    => 0,
            ],
            'akses'    => [
                'admin_url'    => null,
                'extra_access' => null,
                'password_loc' => null,
            ],
            'finansial' => [
                'sell_price'   => 0,
                'pay_system'   => 'Tahunan',
                'pay_status'   => 'Belum',
                'invoice_date' => null,
            ],
        ];

        if (!array_key_exists($section, $sectionFields)) {
            abort(422, 'Section tidak valid untuk operasi clear.');
        }

        $website->update($sectionFields[$section]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Data section berhasil direset!']);
        }
        return back()->with('success', 'Data section berhasil direset!');
    }

    public function show(Website $website)
    {
        return response()->json($website);
    }

    private function validationRules(string $section): array
    {
        return match ($section) {
            'master' => [
                'client'          => 'required|string|max:100',
                'pic'             => 'required|string|max:100',
                'website'         => 'required|string|max:100',
                'url'             => 'required|string|max:200',
                'type'            => 'required|string',
                'technology'      => 'required|string',
                'status'          => 'required|in:Active,InActive,Suspend',
                'prioritas'       => 'nullable|string|max:50',
                'internal_pic'    => 'required|string',
                'service_package' => 'nullable|string',
                'created_year'    => 'nullable|date',
                'note'            => 'nullable|string',
                'phone'           => 'required|string|max:20',
                'email'           => 'nullable|email',
            ],
            'domain' => [
                'url'              => 'nullable|string|max:200',
                'domain_provider'  => 'nullable|string',
                'domain_email'     => 'nullable|email',
                'domain_reg_date'  => 'nullable|date',
                'domain_exp_date'  => 'nullable|date',
                'domain_price'     => 'nullable|numeric|min:0',
                'domain_duration'  => 'nullable|integer|min:1',
                'is_auto_renew'    => 'nullable|boolean',
            ],
            'hosting' => [
                'hosting_type'     => 'nullable|string',
                'hosting_provider' => 'nullable|string',
                'storage'          => 'nullable|numeric|min:0',
                'ip_server'        => ['nullable', 'regex:/^(\d{1,3}\.){3}\d{1,3}$/'],
                'location'         => 'nullable|string',
                'hosting_email'    => 'nullable|email',
                'hosting_exp_date' => 'nullable|date',
                'hosting_price'    => 'nullable|numeric|min:0',
            ],
            'akses' => [
                'admin_url'    => 'nullable|string',
                'extra_access' => 'nullable|string',
                'password_loc' => 'nullable|string',
                'note'         => 'nullable|string',
            ],
            'finansial' => [
                'sell_price'   => 'nullable|numeric|min:0',
                'pay_system'   => 'nullable|in:Tahunan,Bulanan',
                'pay_status'   => 'nullable|in:Lunas,Belum',
                'invoice_date' => 'nullable|date',
            ],
            'reminder' => [
                'note' => 'nullable|string',
            ],
            default => $this->validationRules($section),
        };
    }

    private function buildStatsData(string $section, $all): array
    {
        return match ($section) {
            'master' => [
                'active'   => $all->where('status', 'Active')->count(),
                'inactive' => $all->where('status', 'InActive')->count(),
                'suspend'  => $all->where('status', 'Suspend')->count(),
                'total'    => $all->count(),
            ],
            'domain' => [
                'providers' => $all->whereNotNull('domain_provider')->groupBy('domain_provider')
                    ->map->count()->sortDesc()->take(6)->toArray(),
            ],
            'hosting' => [
                'expiry_cards' => $all->whereNotNull('hosting_exp_date')
                    ->map(fn($w) => [
                        'website'  => $w->website,
                        'client'   => $w->client,
                        'exp_date' => $w->hosting_exp_date?->format('d/m/Y'),
                        'days'     => $w->days_remaining,
                        'status'   => $w->reminder_status,
                    ])
                    ->sortBy('days')->values()->toArray(),
            ],
            'akses' => [
                'has_admin_url'    => $all->filter(fn($w) => !empty($w->admin_url))->count(),
                'has_extra_access' => $all->filter(fn($w) => !empty($w->extra_access))->count(),
                'has_password_loc' => $all->filter(fn($w) => !empty($w->password_loc))->count(),
                'total'            => $all->count(),
            ],
            'finansial' => [
                'total_revenue' => $all->sum('sell_price'),
                'total_domain'  => $all->sum('domain_price'),
                'total_hosting' => $all->sum('hosting_price'),
                'total_margin'  => $all->sum(fn($w) => $w->margin),
                'lunas'         => $all->where('pay_status', 'Lunas')->count(),
                'belum'         => $all->where('pay_status', 'Belum')->count(),
                'margins'       => $all->map(fn($w) => [
                    'website' => $w->website ?? $w->client,
                    'margin'  => $w->margin,
                ])->sortByDesc('margin')->take(8)->values()->toArray(),
            ],
            'reminder' => [
                // --- Hosting ---
                'aman'      => $all->filter(fn($w) => $w->reminder_status === 'Aman')->count(),
                'siaga'     => $all->filter(fn($w) => $w->reminder_status === 'Segera')->count(),
                'darurat'   => $all->filter(fn($w) => in_array($w->reminder_status, ['Kritis', 'Expired']))->count(),
                'deadlines' => $all->whereNotNull('hosting_exp_date')
                    ->map(fn($w) => [
                        'website'  => $w->website,
                        'days'     => $w->days_remaining,
                        'status'   => $w->reminder_status,
                        'exp_date' => $w->hosting_exp_date?->format('d/m/Y'),
                    ])
                    ->sortBy('days')->take(8)->values()->toArray(),
                // --- Domain ---
                'domain_aman'      => $all->filter(fn($w) => $w->domain_reminder_status === 'Aman')->count(),
                'domain_siaga'     => $all->filter(fn($w) => $w->domain_reminder_status === 'Segera')->count(),
                'domain_darurat'   => $all->filter(fn($w) => in_array($w->domain_reminder_status, ['Kritis', 'Expired']))->count(),
                'domain_deadlines' => $all->whereNotNull('domain_exp_date')
                    ->map(fn($w) => [
                        'website'  => $w->website,
                        'days'     => $w->domain_days_remaining,
                        'status'   => $w->domain_reminder_status,
                        'exp_date' => $w->domain_exp_date?->format('d/m/Y'),
                    ])
                    ->sortBy('days')->take(8)->values()->toArray(),
            ],
            default => [],
        };
    }

    public function search(Request $request)
    {
        $search  = $request->get('search', '');
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;
        $section = $request->get('section', 'master');
        $sortBy  = $request->get('sort_by') ?? '';
        $sortDir = ($request->get('sort_dir') ?? 'asc') === 'desc' ? 'desc' : 'asc';

        $query = Website::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(client) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(website) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
        $this->applySorting($query, $sortBy, $sortDir);

        $websites  = $query->paginate($perPage)->withQueryString();
        $columns   = $this->sectionColumns[$section] ?? $this->sectionColumns['master'];
        $dropdowns = DropdownConfig::forPage($section);

        return response()->json([
            'html' => view('components.data-table-body', compact(
                'websites',
                'columns',
                'section',
                'search',
                'perPage',
                'dropdowns'
            ))->render(),
            'total'       => $websites->total(),
            'from'        => $websites->firstItem(),
            'to'          => $websites->lastItem(),
            'currentPage' => $websites->currentPage(),
            'lastPage'    => $websites->lastPage(),
            'sortBy'      => $sortBy,
            'sortDir'     => $sortDir,
        ]);
    }

    public function exportFinansial()
    {
        $websites = Website::orderBy('client', 'asc')->get();

        $filename = 'finansial_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($websites) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header row
            fputcsv($handle, [
                'No',
                'Client',
                'Website',
                'URL',
                'Harga Domain (Rp)',
                'Harga Hosting (Rp)',
                'Harga Jual (Rp)',
                'Margin (Rp)',
                'Sistem Bayar',
                'Status Bayar',
                'Tanggal Invoice',
            ]);

            foreach ($websites as $index => $w) {
                $margin = $w->margin;

                fputcsv($handle, [
                    $index + 1,
                    $w->client,
                    $w->website,
                    $w->url,
                    $w->domain_price,
                    $w->hosting_price,
                    $w->sell_price,
                    $margin,
                    $w->pay_system,
                    $w->pay_status,
                    $w->invoice_date?->format('d/m/Y') ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
