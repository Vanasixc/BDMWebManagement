{{-- 
    Icon Component — menggunakan SVG inline Lucide-style icons
    Usage: @include('components.icon', ['name' => 'globe', 'class' => 'w-5 h-5'])
--}}
@php
    $class = $class ?? 'w-5 h-5';
    $stroke = $stroke ?? 'currentColor';
@endphp

@switch($name)

@case('layout-dashboard')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM14 11a1 1 0 011-1h4a1 1 0 011 1v8a1 1 0 01-1 1h-4a1 1 0 01-1-1v-8z"/>
</svg>
@break

@case('database')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7c0-1.657 3.582-3 8-3s8 1.343 8 3v10c0 1.657-3.582 3-8 3s-8-1.343-8-3V7z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7c0 1.657 3.582 3 8 3s8-1.343 8-3"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12c0 1.657 3.582 3 8 3s8-1.343 8-3"/>
</svg>
@break

@case('globe')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
</svg>
@break

@case('server')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
</svg>
@break

@case('key')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
</svg>
@break

@case('dollar-sign')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@break

@case('bell')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
</svg>
@break

@case('users')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
</svg>
@break

@case('check-circle')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@break

@case('clock')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@break

@case('alert-triangle')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
</svg>
@break

@case('plus')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
</svg>
@break

@case('edit')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
</svg>
@break

@case('trash')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
</svg>
@break

@case('eye')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
</svg>
@break

@case('settings')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
</svg>
@break

@case('x')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
</svg>
@break

@case('search')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
</svg>
@break

@case('shield-check')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
</svg>
@break

@case('bar-chart-2')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 20V10M12 20V4M6 20v-6"/>
</svg>
@break

@case('link')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
</svg>
@break

@case('lock')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
</svg>
@break

@case('credit-card')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
</svg>
@break

@case('tag')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
</svg>
@break

@case('calendar')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
</svg>
@break

@case('trending-up')
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
</svg>
@break

@default
<svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="{{ $stroke }}">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endswitch
