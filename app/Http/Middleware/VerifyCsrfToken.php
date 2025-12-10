<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // --- SMS Gateway রুটটিকে এখানে যোগ করুন ---
        // এই URL-এ আসা POST রিকোয়েস্টের জন্য CSRF টোকেন চেক করা হবে না।
        'sms-gateway-receiver', 
        // ----------------------------------------
    ];
}