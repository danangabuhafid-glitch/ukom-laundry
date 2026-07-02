<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransOrder;
use App\Models\TransLaundryPickup;
use Illuminate\Support\Facades\DB;
use Exception;

class PickupController extends Controller
{

    public function index(Request $request)
    {
        $query = TransOrder::with(['customer', 'transLaundryPickup'])->latest();
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('customer_name', 'like', "%{$search}%");
                  });
        }
        
        $orders = $query->paginate(10)->withQueryString();
        
        if ($request->ajax()) {
            return view('transactions.pickups.table', compact('orders'))->render();
        }

        return view('transactions.pickups.index', compact('orders'));
    }

    public function store(Request $request, $id)
    {
        $order = TransOrder::findOrFail($id);
        
        try {
            if ($order->order_status != 0) {
                throw new Exception("Order is already picked up or cannot be picked up.");
            }

            DB::transaction(function () use ($order, $request) {
                $order->update(['order_status' => 1]); // 1 = Sudah Diambil

                $notes = $request->input('notes');
                $defaultNote = 'Confirmed by: ' . auth()->user()->name;
                $finalNote = $notes ? $notes . ' (by: ' . auth()->user()->name . ')' : $defaultNote;

                TransLaundryPickup::create([
                    'id_order' => $order->id,
                    'id_customer' => $order->id_customer,
                    'pickup_date' => now(),
                    'notes' => $finalNote,
                ]);
            });

            return back()->with('success', 'Order picked up successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
