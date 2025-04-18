<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attribute;
use App\Models\UserAttribute;
use Illuminate\Http\Request;

class UserAttributeController extends Controller
{
    public function index(User $user)
    {
        $userAttributes = $user->userAttributes()->with('attribute')->get();
        $availableAttributes = Attribute::where('entity_type', 'user')->get();
        
        return view('abac.users.attributes', compact('user', 'userAttributes', 'availableAttributes'));
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string'
        ]);

        // Aynı öznitelik varsa güncelle, yoksa yeni ekle
        $user->userAttributes()->updateOrCreate(
            ['attribute_id' => $validated['attribute_id']],
            ['value' => $validated['value']]
        );

        return redirect()->route('abac.users.attributes', $user)
            ->with('success', 'Kullanıcı özniteliği başarıyla eklendi.');
    }

    public function update(Request $request, User $user, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string'
        ]);

        $user->userAttributes()
            ->where('attribute_id', $attribute->id)
            ->update(['value' => $validated['value']]);

        return redirect()->route('abac.users.attributes', $user)
            ->with('success', 'Kullanıcı özniteliği başarıyla güncellendi.');
    }

    public function destroy(User $user, Attribute $attribute)
    {
        $user->userAttributes()
            ->where('attribute_id', $attribute->id)
            ->delete();

        return redirect()->route('abac.users.attributes', $user)
            ->with('success', 'Kullanıcı özniteliği başarıyla silindi.');
    }
} 