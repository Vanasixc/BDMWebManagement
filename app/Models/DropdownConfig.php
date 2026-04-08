<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropdownConfig extends Model
{
    protected $fillable = ['page', 'key', 'label', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Ambil semua config untuk satu halaman, dalam format key => options.
     */
    public static function forPage(string $page): \Illuminate\Support\Collection
    {
        return static::where('page', $page)->get()->keyBy('key');
    }

    /**
     * Ambil opsi untuk satu key dalam satu page.
     */
    public static function getOptions(string $page, string $key): array
    {
        $config = static::where('page', $page)->where('key', $key)->first();
        return $config ? $config->options : [];
    }
}
