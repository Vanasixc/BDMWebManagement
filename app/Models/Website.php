<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        // Info Klien
        'client',
        'jenis_klien', // NOTE: Kolom diganti nama sesuai schema baru
        'pic',
        'phone',       // NOTE: Ditambahkan nomor telepon klien
        'email',

        // Info Website
        'website',
        'url',
        'type',
        'technology',
        'status',
        'internal_pic',
        'service_package',
        'created_year',
        'note',

        // Info Domain
        'domain_name', // NOTE: Ditambahkan untuk membedakan nama domain & URL
        'domain_provider',
        'domain_email',
        'domain_reg_date',
        'domain_exp_date',
        'domain_duration', // NOTE: Durasi perpanjangan domain dalam tahun
        'is_auto_renew',   // NOTE: Flag untuk auto renew domain
        'domain_price',

        // Info Hosting
        'hosting_type',
        'hosting_provider',
        'hosting_package', // NOTE: Menyimpan nama paket hosting spesifik
        'storage',
        'ip_server',
        'location',
        'hosting_email',
        'hosting_exp_date',
        'hosting_price',

        // Info Akses
        'admin_url',
        'admin_username',  // NOTE: Dipisahkan untuk username login akses admin
        'extra_access',
        'password_loc',

        // Info Finansial
        'sell_price',
        'pay_system',
        'pay_status',
        'invoice_date',
    ];

    protected $casts = [
        'domain_reg_date'  => 'date',
        'domain_exp_date'  => 'date',
        'hosting_exp_date' => 'date',
        'invoice_date'     => 'date',
        'created_year'     => 'string',
        'domain_duration'  => 'integer',
        'is_auto_renew'    => 'boolean',
        'domain_price'     => 'decimal:2',
        'hosting_price'    => 'decimal:2',
        'sell_price'       => 'decimal:2',
    ];

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->hosting_exp_date) return 0;

        return (int) now()->startOfDay()->diffInDays(
            $this->hosting_exp_date->startOfDay(),
            false
        );
    }

    public function getMarginAttribute(): int
    {
        $monthlyHosting = $this->hosting_price / 12;
        return (int) ($this->sell_price - ($this->domain_price + $monthlyHosting));
    }

    public function getReminderStatusAttribute(): string
    {
        $days = $this->days_remaining;
        if ($days < 0) return 'Expired';
        if ($days < 7) return 'Kritis';
        if ($days < 30) return 'Segera';
        return 'Aman';
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where('client', 'like', "%{$keyword}%")
            ->orWhere('website', 'like', "%{$keyword}%");
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superAdmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function canModify(): bool
    {
        return in_array($this->role, ['superAdmin', 'admin']);
    }

    public function isIncomplete(string $section): bool
    {
        return match ($section) {
            'domain'    => empty($this->domain_provider) || empty($this->domain_email) ||
                empty($this->domain_reg_date) || empty($this->domain_exp_date) ||
                empty($this->domain_price),
            'hosting'   => empty($this->hosting_type) || empty($this->hosting_provider) ||
                empty($this->storage) || empty($this->ip_server) ||
                empty($this->location) || empty($this->hosting_email) ||
                empty($this->hosting_exp_date) || empty($this->hosting_price),
            'akses'     => empty($this->admin_url) || empty($this->extra_access) ||
                empty($this->password_loc),
            'finansial' => empty($this->sell_price) || empty($this->pay_status) ||
                empty($this->pay_system) || empty($this->invoice_date),
            'reminder'  => empty($this->hosting_exp_date) || empty($this->domain_exp_date),
            default     => false,
        };
    }

    public function isAllEmpty(string $section): bool
    {
        return match ($section) {
            'domain'    => empty($this->domain_provider) && empty($this->domain_email) &&
                empty($this->domain_reg_date) && empty($this->domain_exp_date) &&
                empty($this->domain_price),
            'hosting'   => empty($this->hosting_type) && empty($this->hosting_provider) &&
                empty($this->storage) && empty($this->ip_server) &&
                empty($this->location) && empty($this->hosting_email) &&
                empty($this->hosting_exp_date) && empty($this->hosting_price),
            'akses'     => empty($this->admin_url) && empty($this->extra_access) &&
                empty($this->password_loc),
            'finansial' => empty($this->sell_price) && empty($this->pay_status) &&
                empty($this->pay_system) && empty($this->invoice_date),
            'reminder'  => empty($this->hosting_exp_date) && empty($this->domain_exp_date),
            default     => false,
        };
    }
}
