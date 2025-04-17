<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAbacAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userAttributes = $user->attributes;

        // ABAC yönetim paneline erişim için gerekli öznitelikler
        $requiredAttributes = [
            'role' => ['Admin', 'System Administrator'],
            'security_clearance' => 4,
            'is_admin' => true
        ];

        foreach ($requiredAttributes as $attribute => $value) {
            $userAttribute = $userAttributes->where('attribute.name', $attribute)->first();
            
            if (!$userAttribute) {
                return redirect()->route('home')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
            }

            if (is_array($value)) {
                if (!in_array($userAttribute->value, $value)) {
                    return redirect()->route('home')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
                }
            } else {
                if ($userAttribute->value < $value) {
                    return redirect()->route('home')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
                }
            }
        }

        return $next($request);
    }
} 