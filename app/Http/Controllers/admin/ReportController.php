<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the main reports page.
     */
    public function index()
    {
        // 1. Fetch Monthly Sales Data
        $salesData = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total_sales'),
            DB::raw('COUNT(*) as total_orders')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        // 2. Fetch Promo Code Usage Data
        $promoData = DB::table('promos')
            ->join('orders', 'promos.code', '=', 'orders.promo_code')
            ->select(
                'promos.code',
                'promos.type',
                'promos.value',
                // --- THIS LINE IS THE FIX ---
                DB::raw('count(orders.id_pesanan) as usage_count') 
            )
            ->whereNotNull('orders.promo_code')
            ->groupBy('promos.code', 'promos.type', 'promos.value')
            ->orderBy('usage_count', 'desc')
            ->get();

        // 3. Fetch User Registrations Data
        $userData = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_users')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        // 4. Fetch Top 10 Customers Data
        $topCustomers = User::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(10)
            ->get();

        // Return the single, merged view with all the data compacted
        return view('admin.report', compact(
            'salesData', 
            'promoData', 
            'userData', 
            'topCustomers'
        ));
    }
}