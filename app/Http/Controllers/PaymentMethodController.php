<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('id')->get();
        return view('financial.payment-methods', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name',
        ]);

        $code = Str::slug($request->name, '_');
        if (PaymentMethod::where('code', $code)->exists()) {
            $code .= '_' . time();
        }

        PaymentMethod::create([
            'name' => $request->name,
            'code' => $code,
            'is_active' => true,
            'is_system' => false,
        ]);

        return back()->with('success', 'Payment method added.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name,' . $paymentMethod->id,
        ]);

        if (!$paymentMethod->is_system) {
            $paymentMethod->name = $request->name;
        }

        $paymentMethod->is_active = $request->has('is_active');
        $paymentMethod->save();

        return back()->with('success', 'Payment method updated.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->is_system) {
            return back()->with('error', 'System payment method cannot be deleted.');
        }

        $paymentMethod->delete();
        return back()->with('success', 'Payment method deleted.');
    }
}