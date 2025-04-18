<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'effect',
        'conditions',
        'resource',
        'action',
        'priority'
    ];

    protected $casts = [
        'conditions' => 'array',
        'priority' => 'integer'
    ];

    const EFFECTS = [
        'allow',
        'deny'
    ];

    public function policyAttributes(): HasMany
    {
        return $this->hasMany(PolicyAttribute::class);
    }

    public function resourceAttributes(): HasMany
    {
        return $this->hasMany(ResourceAttribute::class);
    }

    public function evaluate(array $userAttributes, array $context = []): bool
    {
        // Önce öznitelik eşleşmelerini kontrol et
        $attributeMatch = $this->evaluateAttributes($userAttributes);
        if (!$attributeMatch) {
            return false;
        }

        // Sonra ek koşulları kontrol et
        return $this->evaluateConditions($context);
    }

    private function evaluateAttributes(array $userAttributes): bool
    {
        $matches = true;

        foreach ($this->policyAttributes as $policyAttribute) {
            $userAttribute = $userAttributes[$policyAttribute->attribute->name] ?? null;
            
            if (!$userAttribute) {
                return false;
            }

            $matches = $matches && $this->compare(
                $userAttribute,
                $policyAttribute->operator,
                $this->resolveValue($policyAttribute->value, $userAttributes)
            );
        }

        return $matches;
    }

    private function evaluateConditions(array $context): bool
    {
        if (empty($this->conditions)) {
            return true;
        }

        $conditions = $this->conditions;

        // Zaman bazlı kontroller
        if (isset($conditions['time_based'])) {
            $timeCondition = $conditions['time_based'];
            $now = Carbon::now();
            
            // Gün kontrolü
            if (isset($timeCondition['days']) && !in_array($now->format('l'), $timeCondition['days'])) {
                return false;
            }

            // Saat kontrolü
            if (isset($timeCondition['start']) && isset($timeCondition['end'])) {
                $start = Carbon::createFromFormat('H:i', $timeCondition['start']);
                $end = Carbon::createFromFormat('H:i', $timeCondition['end']);
                
                if (!$now->between($start, $end)) {
                    // Acil durum kontrolü
                    if (!($conditions['emergency_override'] ?? false) || !($context['is_emergency'] ?? false)) {
                        return false;
                    }
                }
            }
        }

        // Gerekli eğitim kontrolü
        if (isset($conditions['required_training'])) {
            $userTraining = $context['user_training'] ?? [];
            $requiredTraining = $conditions['required_training'];
            
            if (array_diff($requiredTraining, $userTraining)) {
                return false;
            }
        }

        return true;
    }

    private function compare($userValue, string $operator, string $policyValue): bool
    {
        return match ($operator) {
            'equals' => $userValue == $policyValue,
            'not_equals' => $userValue != $policyValue,
            'greater_than' => $userValue > $policyValue,
            'less_than' => $userValue < $policyValue,
            'greater_than_or_equal' => $userValue >= $policyValue,
            'less_than_or_equal' => $userValue <= $policyValue,
            'in' => in_array($userValue, json_decode($policyValue, true)),
            'not_in' => !in_array($userValue, json_decode($policyValue, true)),
            'contains' => str_contains($userValue, $policyValue),
            'starts_with' => str_starts_with($userValue, $policyValue),
            'ends_with' => str_ends_with($userValue, $policyValue),
            default => false,
        };
    }

    private function resolveValue(string $value, array $userAttributes): string
    {
        // Dinamik değerleri çözümle
        if (str_starts_with($value, ':')) {
            $key = substr($value, 1);
            return $userAttributes[$key] ?? $value;
        }

        return $value;
    }
}
