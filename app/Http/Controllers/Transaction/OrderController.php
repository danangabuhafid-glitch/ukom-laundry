<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\TransOrder;
use App\Models\Customer;
use App\Models\TypeOfService;
use App\Http\Requests\StoreOrderRequest;
use App\Models\TransOrderDetail;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index(\Illuminate\Http\Request $request)
    {
        $query = TransOrder::with(['customer', 'transOrderDetails.typeOfService', 'transLaundryPickup'])->latest();
        
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('customer_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->paginate(10);
        $customers = Customer::all();
        $services = TypeOfService::all();
        
        $promos = \App\Models\Promo::with('services')
            ->where('is_active', true)
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->get();

        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();

        $taxSetting = \App\Models\TaxSetting::where('key', 'tax_rate')->first();
        $taxRate = $taxSetting ? floatval($taxSetting->value) : 0;

        if ($request->ajax()) {
            return view('transactions.table', compact('orders'))->render();
        }

        return view('transactions.index', compact('orders', 'customers', 'services', 'promos', 'paymentMethods', 'taxRate'));
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $data = $request->validated();
            
            $order = DB::transaction(function () use ($data) {
                if ($data['id_customer'] === 'new') {
                    $customer = \App\Models\Customer::create([
                        'customer_name' => $data['new_customer_name'],
                        'phone' => $data['new_customer_phone'],
                        'address' => $data['new_customer_address'] ?? null,
                    ]);
                    $data['id_customer'] = $customer->id;
                }

                $grandTotal = 0;
                $serviceDetails = [];
                $activePromos = \App\Models\Promo::with('services')
                    ->where('is_active', true)
                    ->whereDate('start_date', '<=', today())
                    ->whereDate('end_date', '>=', today())
                    ->get();
                
                $taxSetting = \App\Models\TaxSetting::where('key', 'tax_rate')->first();
                $taxRate = $taxSetting ? floatval($taxSetting->value) : 0;

                $subtotalBeforeTax = 0;

                $hasAppliedPromo = false;

                foreach ($data['services'] as $svc) {
                    $service = TypeOfService::findOrFail($svc['id_service']);
                    $qty = floatval($svc['qty']);
                    $originalSubtotal = $service->price * $qty;
                    $discountAmount = 0;

                    if (!$hasAppliedPromo) {
                        // Find applicable promo
                        $promo = $activePromos->first(function ($p) use ($service) {
                            return $p->services->isEmpty() || $p->services->contains('id', $service->id);
                        });

                        if ($promo && $qty >= floatval($promo->min_qty)) {
                            if ($promo->discount_type === 'percent') {
                                $discountAmount = $originalSubtotal * (floatval($promo->discount_value) / 100);
                            } else {
                                $discountAmount = floatval($promo->discount_value);
                            }
                            
                            if ($discountAmount > 0) {
                                $hasAppliedPromo = true;
                            }
                        }
                    }

                    if ($discountAmount > $originalSubtotal) $discountAmount = $originalSubtotal;
                    
                    $finalSubtotal = $originalSubtotal - $discountAmount;
                    $subtotalBeforeTax += $finalSubtotal;
                    
                    $serviceDetails[] = [
                        'id_service' => $service->id,
                        'qty' => $qty,
                        'discount' => $discountAmount,
                        'subtotal' => $finalSubtotal,
                    ];
                }
                
                $taxAmount = $subtotalBeforeTax * ($taxRate / 100);
                $grandTotal = $subtotalBeforeTax + $taxAmount;

                $orderCode = $this->generateOrderCode();
                
                $order = TransOrder::create([
                    'id_customer' => $data['id_customer'],
                    'order_code' => $orderCode,
                    'order_date' => now(),
                    'order_status' => 0, // 0 = Baru
                    'total' => $grandTotal,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'order_pay' => $data['order_pay'],
                    'order_change' => max(0, $data['order_pay'] - $grandTotal),
                    'payment_method' => $data['payment_method'],
                ]);

                foreach ($serviceDetails as $detail) {
                    TransOrderDetail::create([
                        'id_order' => $order->id,
                        'id_service' => $detail['id_service'],
                        'qty' => $detail['qty'],
                        'discount' => $detail['discount'],
                        'subtotal' => $detail['subtotal'],
                    ]);
                }

                return $order;
            });

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaction created successfully.',
                    'order_id' => $order->id
                ]);
            }

            return redirect()->route('transactions.show', $order->id)->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error creating transaction: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Error creating transaction: ' . $e->getMessage())->withInput();
        }
    }

    private function generateOrderCode()
    {
        $datePrefix = date('Ymd');
        $lastOrder = TransOrder::whereDate('created_at', today())->latest('id')->first();
        if (!$lastOrder) {
            $number = 1;
        } else {
            $lastCode = $lastOrder->order_code;
            $number = (int) substr($lastCode, -4) + 1;
        }
        return 'TRX-' . $datePrefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function show($id)
    {
        $order = TransOrder::with(['customer', 'transOrderDetails.typeOfService', 'transLaundryPickup'])->findOrFail($id);
        return view('transactions.show', compact('order'));
    }

    public function print($id)
    {
        $order = TransOrder::with(['customer', 'transOrderDetails.typeOfService', 'transLaundryPickup'])->findOrFail($id);
        return view('transactions.print', compact('order'));
    }
}
