<?php

namespace App\Http\Controllers;

use App\Models\DropdownConfig;
use Illuminate\Http\Request;

class DropdownConfigController extends Controller
{
    public function addOption(Request $request)
    {
        $request->validate([
            'page'   => 'required|string',
            'key'    => 'required|string',
            'option' => 'required|string|max:100',
        ]);

        $page   = $request->input('page');
        $key    = $request->input('key');
        $option = $request->input('option');

        $config = DropdownConfig::where('page', $page)
                                ->where('key', $key)
                                ->first();

        if (!$config) {
            return response()->json(['error' => 'Konfigurasi tidak ditemukan.'], 404);
        }

        $options = $config->options ?? [];
        if (!in_array($option, $options)) {
            $options[] = $option;
            $config->update(['options' => $options]);
        }

        return response()->json(['success' => "Opsi \"{$option}\" berhasil ditambahkan."]);
    }

    public function removeOption(Request $request)
    {
        $request->validate([
            'page'   => 'required|string',
            'key'    => 'required|string',
            'option' => 'required|string',
        ]);

        $page   = $request->input('page');
        $key    = $request->input('key');
        $option = $request->input('option');

        $config = DropdownConfig::where('page', $page)
                                ->where('key', $key)
                                ->first();

        if (!$config) {
            return response()->json(['error' => 'Konfigurasi tidak ditemukan.'], 404);
        }

        $options = array_values(array_filter($config->options ?? [], fn($o) => $o !== $option));
        $config->update(['options' => $options]);

        return response()->json(['success' => "Opsi \"{$option}\" berhasil dihapus."]);
    }
}
