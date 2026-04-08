<?php

namespace App\Http\Controllers;

use App\Models\DropdownConfig;
use Illuminate\Http\Request;

class DropdownConfigController extends Controller
{
    /**
     * Tambah opsi baru ke dropdown config.
     */
    public function addOption(Request $request)
    {
        $request->validate([
            'page'   => 'required|string',
            'key'    => 'required|string',
            'option' => 'required|string|max:100',
        ]);

        $config = DropdownConfig::where('page', $request->page)
                                ->where('key', $request->key)
                                ->first();

        if (!$config) {
            return back()->with('error', 'Konfigurasi tidak ditemukan.');
        }

        $options = $config->options;
        if (!in_array($request->option, $options)) {
            $options[] = $request->option;
            $config->update(['options' => $options]);
        }

        return back()->with('success', "Opsi \"{$request->option}\" berhasil ditambahkan.");
    }

    /**
     * Hapus opsi dari dropdown config.
     */
    public function removeOption(Request $request)
    {
        $request->validate([
            'page'   => 'required|string',
            'key'    => 'required|string',
            'option' => 'required|string',
        ]);

        $config = DropdownConfig::where('page', $request->page)
                                ->where('key', $request->key)
                                ->first();

        if (!$config) {
            return back()->with('error', 'Konfigurasi tidak ditemukan.');
        }

        $options = array_values(array_filter($config->options, fn($o) => $o !== $request->option));
        $config->update(['options' => $options]);

        return back()->with('success', "Opsi \"{$request->option}\" berhasil dihapus.");
    }
}
