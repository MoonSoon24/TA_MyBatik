<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|unique:promos,code|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);
        
        $validatedData['current_uses'] = 0;

        Promo::create($validatedData);

        return redirect()->route('admin.home', '#promos')->with('success', 'Promo code created successfully!');
    }
    public function update(Request $request, Promo $promo)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|unique:promos,code,' . $promo->id . '|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $promo->update($validatedData);

        return redirect()->route('admin.home', '#promos')->with('success', 'Promo code updated successfully!');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('admin.home', '#promos')->with('success', 'Promo code deleted successfully.');
    }
}