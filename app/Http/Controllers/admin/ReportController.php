<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
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

        $promoData = DB::table('promos')
            ->join('orders', 'promos.code', '=', 'orders.promo_code')
            ->select(
                'promos.code',
                'promos.type',
                'promos.value',
                DB::raw('count(orders.id_pesanan) as usage_count') 
            )
            ->whereNotNull('orders.promo_code')
            ->groupBy('promos.code', 'promos.type', 'promos.value')
            ->orderBy('usage_count', 'desc')
            ->get();

        $userData = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_users')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        $topCustomers = User::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.report', compact(
            'salesData', 
            'promoData', 
            'userData', 
            'topCustomers'
        ));
    }
}