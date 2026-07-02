<?php

namespace App\Http\Controllers;

use App\Models\TaxSetting;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxRate = TaxSetting::where('key', 'tax_rate')->first();
        return view('financial.tax', compact('taxRate'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        TaxSetting::updateOrCreate(
            ['key' => 'tax_rate'],
            ['value' => $request->tax_rate, 'description' => 'Tax rate in percentage (%)']
        );

        return back()->with('success', 'Tax rate updated.');
    }
}