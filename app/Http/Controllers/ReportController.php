<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = TransOrder::with(['customer', 'transOrderDetails.typeOfService']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate . ' 23:59:59']);
        }
        
        $orders = $query->latest()->get();
        $totalRevenue = $orders->sum('total');
        
        return view('reports.index', compact('orders', 'startDate', 'endDate', 'totalRevenue'));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = TransOrder::with(['customer', 'transOrderDetails.typeOfService']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate . ' 23:59:59']);
        }
        
        $orders = $query->latest()->get();
        $totalRevenue = $orders->sum('total');
        
        return view('reports.print', compact('orders', 'startDate', 'endDate', 'totalRevenue'));
    }
}
