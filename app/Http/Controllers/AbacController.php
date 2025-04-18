<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Policy;
use App\Models\User;
use App\Models\UserAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller as BaseController;

class AbacController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $policies = Policy::with('attributes')->get();
        $attributes = Attribute::all();
        $users = User::with('attributes')->get();

        return view('abac.index', compact('policies', 'attributes', 'users'));
    }

    public function createPolicy()
    {
        $attributes = Attribute::all();
        return view('abac.policies.create', compact('attributes'));
    }

    public function storePolicy(Request $request)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'effect' => 'required|in:allow,deny',
            'resource' => 'required|string',
            'action' => 'required|string',
            'conditions' => 'required|array',
            'attributes' => 'required|array'
        ]);

        $policy = Policy::create($validated);

        foreach ($request->attributes as $attribute) {
            $policy->attributes()->create([
                'attribute_id' => $attribute['id'],
                'operator' => $attribute['operator'],
                'value' => $attribute['value']
            ]);
        }

        return redirect()->route('abac.index')
            ->with('success', 'Politika başarıyla oluşturuldu.');
    }

    public function editPolicy(Policy $policy)
    {
        $attributes = Attribute::all();
        return view('abac.policies.edit', compact('policy', 'attributes'));
    }

    public function updatePolicy(Request $request, Policy $policy)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'effect' => 'required|in:allow,deny',
            'resource' => 'required|string',
            'action' => 'required|string',
            'conditions' => 'required|array',
            'attributes' => 'required|array'
        ]);

        $policy->update($validated);
        $policy->attributes()->delete();

        foreach ($request->attributes as $attribute) {
            $policy->attributes()->create([
                'attribute_id' => $attribute['id'],
                'operator' => $attribute['operator'],
                'value' => $attribute['value']
            ]);
        }

        return redirect()->route('abac.index')
            ->with('success', 'Politika başarıyla güncellendi.');
    }

    public function deletePolicy(Policy $policy)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $policy->delete();
        return redirect()->route('abac.index')
            ->with('success', 'Politika başarıyla silindi.');
    }

    public function manageUserAttributes(User $user)
    {
        $attributes = Attribute::all();
        $userAttributes = $user->attributes;
        return view('abac.users.attributes', compact('user', 'attributes', 'userAttributes'));
    }

    public function updateUserAttributes(Request $request, User $user)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $validated = $request->validate([
            'attributes' => 'required|array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required'
        ]);

        $user->attributes()->delete();

        foreach ($request->attributes as $attribute) {
            $user->attributes()->create([
                'attribute_id' => $attribute['id'],
                'value' => $attribute['value']
            ]);
        }

        return redirect()->route('abac.index')
            ->with('success', 'Kullanıcı öznitelikleri başarıyla güncellendi.');
    }

    public function checkAccess(Request $request)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'resource' => 'required|string',
            'action' => 'required|string'
        ]);

        $user = User::with('attributes')->find($validated['user_id']);
        $policies = Policy::with('attributes')
            ->where('resource', $validated['resource'])
            ->where('action', $validated['action'])
            ->get();

        $result = $this->evaluatePolicies($user, $policies);

        return view('abac.access-result', compact('result', 'user', 'policies'));
    }

    private function evaluatePolicies(User $user, $policies)
    {
        foreach ($policies as $policy) {
            if ($this->evaluatePolicy($user, $policy)) {
                return [
                    'allowed' => $policy->effect === 'allow',
                    'policy' => $policy
                ];
            }
        }

        return ['allowed' => false, 'policy' => null];
    }

    private function evaluatePolicy(User $user, Policy $policy)
    {
        foreach ($policy->attributes as $policyAttribute) {
            $userAttribute = $user->attributes
                ->where('attribute_id', $policyAttribute->attribute_id)
                ->first();

            if (
                !$userAttribute || !$this->evaluateAttribute(
                    $userAttribute->value,
                    $policyAttribute->operator,
                    $policyAttribute->value
                )
            ) {
                return false;
            }
        }

        return true;
    }

    private function evaluateAttribute($userValue, $operator, $policyValue)
    {
        switch ($operator) {
            case 'equals':
                return $userValue == $policyValue;
            case 'not_equals':
                return $userValue != $policyValue;
            case 'greater_than':
                return $userValue > $policyValue;
            case 'less_than':
                return $userValue < $policyValue;
            case 'greater_than_or_equal':
                return $userValue >= $policyValue;
            case 'less_than_or_equal':
                return $userValue <= $policyValue;
            case 'in':
                return in_array($userValue, json_decode($policyValue, true));
            case 'not_in':
                return !in_array($userValue, json_decode($policyValue, true));
            default:
                return false;
        }
    }

    public function create()
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }
        return view('abac.create');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        Attribute::create($validated);

        return redirect()->route('abac.index')
            ->with('success', 'Attribute created successfully.');
    }

    public function show($id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('abac.show', compact('attribute'));
    }

    public function edit($id)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $attribute = Attribute::findOrFail($id);
        return view('abac.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $attribute = Attribute::findOrFail($id);
        $attribute->update($validated);

        return redirect()->route('abac.index')
            ->with('success', 'Attribute updated successfully.');
    }

    public function destroy($id)
    {
        if (!Gate::allows('manage-abac')) {
            abort(403);
        }

        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return redirect()->route('abac.index')
            ->with('success', 'Attribute deleted successfully.');
    }
}