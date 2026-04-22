<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $page
 * @property string $key
 * @property string $label
 * @property array<array-key, mixed> $options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig wherePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DropdownConfig whereUpdatedAt($value)
 */
	class DropdownConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property string|null $avatar
 * @property string|null $display_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $client
 * @property string $pic
 * @property string $website
 * @property string $url
 * @property string $type
 * @property string $technology
 * @property string $status
 * @property string $internal_pic
 * @property string|null $service_package
 * @property \Illuminate\Support\Carbon|null $created_year
 * @property string|null $note
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $domain_provider
 * @property string|null $domain_email
 * @property \Illuminate\Support\Carbon|null $domain_reg_date
 * @property \Illuminate\Support\Carbon|null $domain_exp_date
 * @property int $domain_price
 * @property string|null $hosting_type
 * @property string|null $hosting_provider
 * @property int $storage
 * @property string|null $ip_server
 * @property string|null $location
 * @property string|null $hosting_email
 * @property \Illuminate\Support\Carbon|null $hosting_exp_date
 * @property int $hosting_price
 * @property string|null $admin_url
 * @property string|null $extra_access
 * @property string|null $password_loc
 * @property int $sell_price
 * @property string $pay_system
 * @property string $pay_status
 * @property \Illuminate\Support\Carbon|null $invoice_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $days_remaining
 * @property-read int $margin
 * @property-read string $reminder_status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website search(string $keyword)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereAdminUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereCreatedYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereDomainEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereDomainExpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereDomainPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereDomainProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereDomainRegDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereExtraAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereHostingEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereHostingExpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereHostingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereHostingProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereHostingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereInternalPic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereIpServer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website wherePasswordLoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website wherePayStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website wherePaySystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereSellPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereServicePackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereTechnology($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Website whereWebsite($value)
 */
	class Website extends \Eloquent {}
}

