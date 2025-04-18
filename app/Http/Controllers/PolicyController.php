<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\Attribute;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::with(['policyAttributes.attribute'])->paginate(10);
        return view('abac.policies.index', compact('policies'));
    }

    public function create()
    {
        $attributes = Attribute::all();
        return view('abac.policies.create', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'effect' => 'required|in:allow,deny',
            'resource' => 'required|string',
            'action' => 'required|string',
            'conditions' => 'nullable|array',
            'priority' => 'required|integer'
        ]);

        $policy = Policy::create($validated);

        if ($request->has('attributes')) {
            foreach ($request->attributes as $attributeData) {
                $policy->policyAttributes()->create([
                    'attribute_id' => $attributeData['attribute_id'],
                    'operator' => $attributeData['operator'],
                    'value' => $attributeData['value']
                ]);
            }
        }

        return redirect()->route('abac.policies.index')
            ->with('success', 'Politika başarıyla oluşturuldu.');
    }

    public function edit(Policy $policy)
    {
        $attributes = Attribute::all();
        $policy->load('policyAttributes.attribute');
        return view('abac.policies.edit', compact('policy', 'attributes'));
    }

    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'effect' => 'required|in:allow,deny',
            'resource' => 'required|string',
            'action' => 'required|string',
            'conditions' => 'nullable|array',
            'priority' => 'required|integer'
        ]);

        $policy->update($validated);

        if ($request->has('attributes')) {
            $policy->policyAttributes()->delete();
            foreach ($request->attributes as $attributeData) {
                $policy->policyAttributes()->create([
                    'attribute_id' => $attributeData['attribute_id'],
                    'operator' => $attributeData['operator'],
                    'value' => $attributeData['value']
                ]);
            }
        }

        return redirect()->route('abac.policies.index')
            ->with('success', 'Politika başarıyla güncellendi.');
    }

    public function destroy(Policy $policy)
    {
        $policy->delete();
        return redirect()->route('abac.policies.index')
            ->with('success', 'Politika başarıyla silindi.');
    }
} 