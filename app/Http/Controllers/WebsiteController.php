<?php

namespace App\Http\Controllers;

use App\Models\DropdownConfig;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    // Konfigurasi kolom tabel per section / halaman
    private array $sectionColumns = [
        'master' => [
            ['key' => 'client',       'label' => 'Nama Client'],
            ['key' => 'pic',          'label' => 'PIC'],
            ['key' => 'website',      'label' => 'Nama Website'],
            ['key' => 'type',         'label' => 'Jenis Website'],
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
            ['key' => 'client',       'label' => 'Client'],
            ['key' => 'sell_price',   'label' => 'Harga Jual',  'currency' => true],
            ['key' => 'domain_price', 'label' => 'B. Domain',   'currency' => true],
            ['key' => 'hosting_price','label' => 'B. Hosting',  'currency' => true],
            ['key' => 'margin',       'label' => 'Margin',      'currency' => true, 'computed' => true, 'highlight' => 'emerald'],
            ['key' => 'pay_status',   'label' => 'Status',      'pay_badge' => true],
        ],
        'reminder' => [
            ['key' => 'website',          'label' => 'Website'],
            ['key' => 'domain_exp_date',  'label' => 'Exp Domain',  'date' => true],
            ['key' => 'hosting_exp_date', 'label' => 'Exp Hosting', 'date' => true],
            ['key' => 'reminder_status',  'label' => 'Status',      'reminder_badge' => true, 'computed' => true],
        ],
    ];

    /**
     * List website dengan filter section.
     */
    public function index(Request $request, string $section = 'master')
    {
        $search    = $request->get('search', '');
        $perPage   = (int) $request->get('per_page', 10);
        $perPage   = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $query = Website::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('client', 'like', "%{$search}%")
                  ->orWhere('website', 'like', "%{$search}%");
            });
        }

        $websites    = $query->paginate($perPage)->withQueryString();
        $columns     = $this->sectionColumns[$section] ?? $this->sectionColumns['master'];
        $dropdowns   = DropdownConfig::forPage($section);

        return view("sections.{$section}", compact('websites', 'columns', 'section', 'search', 'perPage', 'dropdowns'));
    }

    /**
     * Simpan website baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        Website::create($validated);

        return back()->with('success', 'Data website berhasil ditambahkan!');
    }

    /**
     * Update website.
     */
    public function update(Request $request, Website $website)
    {
        $validated = $request->validate($this->validationRules());
        $website->update($validated);

        return back()->with('success', 'Data website berhasil diperbarui!');
    }

    /**
     * Hapus website.
     */
    public function destroy(Website $website)
    {
        $website->delete();
        return back()->with('success', 'Data website berhasil dihapus!');
    }

    /**
     * Ambil data website untuk modal (JSON).
     */
    public function show(Website $website)
    {
        return response()->json($website);
    }

    /**
     * Aturan validasi terpusat.
     */
    private function validationRules(): array
    {
        return [
            'client'           => 'required|string|max:100',
            'pic'              => 'required|string|max:100',
            'website'          => 'required|string|max:100',
            'url'              => 'required|string|max:200',
            'type'             => 'required|string',
            'technology'       => 'required|string',
            'status'           => 'required|in:Aktif,InActive,Suspend',
            'internal_pic'     => 'required|string',
            'service_package'  => 'nullable|string',
            'created_year'     => 'nullable|date',
            'note'             => 'nullable|string',
            'phone'            => 'nullable|string|max:20',
            'email'            => 'nullable|email',
            'domain_provider'  => 'nullable|string',
            'domain_email'     => 'nullable|email',
            'domain_reg_date'  => 'nullable|date',
            'domain_exp_date'  => 'nullable|date',
            'domain_price'     => 'nullable|integer|min:0',
            'hosting_type'     => 'nullable|string',
            'hosting_provider' => 'nullable|string',
            'storage'          => 'nullable|integer|min:0',
            'ip_server'        => 'nullable|string',
            'location'         => 'nullable|string',
            'hosting_email'    => 'nullable|email',
            'hosting_exp_date' => 'nullable|date',
            'hosting_price'    => 'nullable|integer|min:0',
            'admin_url'        => 'nullable|string',
            'extra_access'     => 'nullable|string',
            'password_loc'     => 'nullable|string',
            'sell_price'       => 'nullable|integer|min:0',
            'pay_system'       => 'nullable|in:Tahunan,Bulanan',
            'pay_status'       => 'nullable|in:Lunas,Belum',
            'invoice_date'     => 'nullable|date',
        ];
    }
}
