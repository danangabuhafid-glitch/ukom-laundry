<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\TypeOfService;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::with('services')->latest()->get();
        $services = TypeOfService::all();
        return view('promos.index', compact('promos', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'services' => 'nullable|array',
            'services.*' => 'exists:type_of_service,id',
            'discount_type' => 'required|in:percent,nominal',
            'discount_value' => 'required|numeric|min:0',
            'min_qty' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean'
        ]);

        $data['is_active'] = $request->has('is_active');

        $promo = Promo::create($data);
        if ($request->has('services') && !empty($request->services)) {
            $promo->services()->sync($request->services);
        } else {
            $promo->services()->sync([]);
        }

        return redirect()->back()->with('success', 'Promo created successfully.');
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'services' => 'nullable|array',
            'services.*' => 'exists:type_of_service,id',
            'discount_type' => 'required|in:percent,nominal',
            'discount_value' => 'required|numeric|min:0',
            'min_qty' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean'
        ]);

        $data['is_active'] = $request->has('is_active');

        $promo->update($data);
        if ($request->has('services') && !empty($request->services)) {
            $promo->services()->sync($request->services);
        } else {
            $promo->services()->sync([]);
        }

        return redirect()->back()->with('success', 'Promo updated successfully.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->back()->with('success', 'Promo deleted successfully.');
    }
}
