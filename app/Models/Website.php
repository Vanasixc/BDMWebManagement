<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        // Master
        'client', 'pic', 'website', 'url', 'type', 'technology', 'status',
        'internal_pic', 'service_package', 'created_year', 'note', 'phone', 'email',

        // Domain
        'domain_provider', 'domain_email', 'domain_reg_date', 'domain_exp_date', 'domain_price',

        // Hosting
        'hosting_type', 'hosting_provider', 'storage', 'ip_server', 'location',
        'hosting_email', 'hosting_exp_date', 'hosting_price',

        // Access
        'admin_url', 'extra_access', 'password_loc',

        // Financial
        'sell_price', 'pay_system', 'pay_status', 'invoice_date',
    ];

    protected $casts = [
        'domain_reg_date'  => 'date',
        'domain_exp_date'  => 'date',
        'hosting_exp_date' => 'date',
        'invoice_date'     => 'date',
        'created_year'     => 'date',
        'domain_price'     => 'integer',
        'hosting_price'    => 'integer',
        'sell_price'       => 'integer',
        'storage'          => 'integer',
    ];

    /**
     * Hitung sisa hari hosting.
     */
    public function getDaysRemainingAttribute(): int
    {
        if (!$this->hosting_exp_date) return 0;
        return (int) now()->diffInDays($this->hosting_exp_date, false);
    }

    /**
     * Hitung margin finansial.
     */
    public function getMarginAttribute(): int
    {
        $monthlyHosting = $this->hosting_price / 12;
        return (int) ($this->sell_price - ($this->domain_price + $monthlyHosting));
    }

    /**
     * Status reminder berdasarkan sisa hari hosting.
     */
    public function getReminderStatusAttribute(): string
    {
        $days = $this->days_remaining;
        if ($days < 0) return 'Expired';
        if ($days < 7) return 'Kritis';
        if ($days < 30) return 'Segera';
        return 'Aman';
    }

    /**
     * Scope: filter by section page (digunakan di semua controller).
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('client', 'like', "%{$keyword}%")
                     ->orWhere('website', 'like', "%{$keyword}%");
    }
}
