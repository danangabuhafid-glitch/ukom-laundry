<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = \App\Models\Customer::count();
        $totalTransactions = \App\Models\TransOrder::count();
        $totalRevenue = \App\Models\TransOrder::sum('total');
        $pendingPickups = \App\Models\TransOrder::where('order_status', 0)->count();
        
        $recentTransactions = \App\Models\TransOrder::with('customer')
            ->latest()
            ->take(5)
            ->get();
            
        // Chart data for last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $revenue = \App\Models\TransOrder::whereDate('order_date', $date)->sum('total');
            $chartData['labels'][] = \Carbon\Carbon::parse($date)->format('d M');
            $chartData['series'][] = $revenue;
        }

        return view('dashboard', compact(
            'totalCustomers', 
            'totalTransactions', 
            'totalRevenue', 
            'pendingPickups',
            'recentTransactions',
            'chartData'
        ));
    }
}
