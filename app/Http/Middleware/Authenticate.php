<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Periksa apakah request ini adalah request API (mengharapkan JSON).
        // Jika iya, JANGAN redirect. Cukup kembalikan null,
        // dan Laravel akan secara otomatis mengirimkan respons 401 Unauthorized JSON.
        // Jika tidak, baru redirect ke rute bernama 'login' (untuk web biasa).
        return $request->expectsJson() ? null : route('login');
    }
}
