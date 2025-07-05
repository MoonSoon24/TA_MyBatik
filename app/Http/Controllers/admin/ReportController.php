<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Promo;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales()
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

        return view('admin.report.sales', compact('salesData'));
    }

    public function promoUsage()
    {
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

        return view('admin.report.promo', compact('promoData'));
    }

    public function userRegistrations()
    {
        $userData = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_users')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return view('admin.report.user', compact('userData'));
    }

    public function topCustomers()
    {
        $topCustomers = User::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(10) 
            ->get();

        return view('admin.report.customer', compact('topCustomers'));
    }
}