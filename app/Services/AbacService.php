<?php

namespace App\Services;

use App\Models\Policy;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AbacService
{
    public function checkAccess(User $user, string $resource, string $action, array $context = []): bool
    {
        $cacheKey = "abac:{$user->id}:{$resource}:{$action}:" . md5(json_encode($context));
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user, $resource, $action, $context) {
            $userAttributes = $this->getUserAttributes($user);
            $policies = Policy::where('resource', $resource)
                ->where('action', $action)
                ->get();

            $hasAllowPolicy = false;

            foreach ($policies as $policy) {
                if ($policy->evaluate($userAttributes, $context)) {
                    if ($policy->effect === 'deny') {
                        return false;
                    }
                    $hasAllowPolicy = true;
                }
            }

            return $hasAllowPolicy;
        });
    }

    private function getUserAttributes(User $user): array
    {
        $attributes = [];
        
        foreach ($user->userAttributes as $userAttribute) {
            $attributes[$userAttribute->attribute->name] = $userAttribute->value;
        }

        // Kullanıcıya özel dinamik değerleri ekle
        $attributes['current_user_id'] = $user->id;

        return $attributes;
    }
} 