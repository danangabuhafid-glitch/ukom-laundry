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
        $query = TransOrder::with(['customer'])->latest();
        
        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }
        
        $orders = $query->paginate(10);
        
        if ($request->ajax()) {
            return view('transactions.table', compact('orders'))->render();
        }
        
        return view('transactions.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $services = TypeOfService::all();
        return view('transactions.create', compact('customers', 'services'));
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

                $service = TypeOfService::findOrFail($data['id_service']);
                $subtotal = $service->price * $data['qty'];
                
                $orderCode = $this->generateOrderCode();
                
                $order = TransOrder::create([
                    'id_customer' => $data['id_customer'],
                    'order_code' => $orderCode,
                    'order_date' => now(),
                    'order_status' => 0, // 0 = Baru
                    'total' => $subtotal,
                    'order_pay' => $data['order_pay'],
                    'order_change' => max(0, $data['order_pay'] - $subtotal),
                ]);

                TransOrderDetail::create([
                    'id_order' => $order->id,
                    'id_service' => $service->id,
                    'qty' => $data['qty'],
                    'subtotal' => $subtotal,
                ]);

                return $order;
            });

            return redirect()->route('transactions.show', $order->id)->with('success', 'Transaction created successfully.');
        } catch (\Exception $e) {
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
