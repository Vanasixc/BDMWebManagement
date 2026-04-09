<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $websites = Website::all();

        $stats = [
            'active'   => $websites->where('status', 'Active')->count(),
            'inactive' => $websites->where('status', 'InActive')->count(),
            'suspend'  => $websites->where('status', 'Suspend')->count(),
        ];

        $expiring = $websites->filter(function ($w) {
            if (!$w->hosting_exp_date) return false;
            $days = $w->days_remaining;
            return $days > 0 && $days <= 30;
        })->sortBy('days_remaining')->values();

        $expired = $websites->filter(function ($w) {
            if (!$w->hosting_exp_date) return false;
            return $w->days_remaining < 0;
        })->sortBy('days_remaining')->values();

        // Data revenue hardcoded (bisa dijadikan model tersendiri nantinya)
        $revenueData = [
            ['year' => '2021', 'revenue' => 45000000, 'margin' => 15000000],
            ['year' => '2022', 'revenue' => 62000000, 'margin' => 22000000],
            ['year' => '2023', 'revenue' => 85000000, 'margin' => 31000000],
            ['year' => '2024', 'revenue' => 110000000, 'margin' => 42000000],
        ];

        $domainPriceData = $websites->map(fn($w) => [
            'client' => $w->client,
            'price'  => $w->domain_price,
        ])->values();

        return view('dashboard.index', compact(
            'stats', 'expiring', 'expired', 'revenueData', 'domainPriceData', 'websites'
        ));
    }
}
