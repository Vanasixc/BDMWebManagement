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

        $expiringDomain = $websites->filter(function ($w) {
            if (!$w->domain_exp_date) return false;
            $days = $w->domain_days_remaining;
            return $days > 0 && $days <= 30;
        })->sortBy('domain_days_remaining')->values();

        $expiredDomain = $websites->filter(function ($w) {
            if (!$w->domain_exp_date) return false;
            return $w->domain_days_remaining < 0;
        })->sortBy('domain_days_remaining')->values();

        $revenueData = [
            'revenue' => (int) $websites->sum('sell_price'),
            'margin'  => (int) $websites->sum(fn($w) => $w->margin),
        ];

        $domainPriceData = $websites->map(fn($w) => [
            'client' => $w->client,
            'price'  => $w->domain_price,
        ])->values();

        return view('sections.dashboard', compact(
            'stats',
            'expiring',
            'expired',
            'expiringDomain',
            'expiredDomain',
            'revenueData',
            'domainPriceData'
        ));
    }
}
