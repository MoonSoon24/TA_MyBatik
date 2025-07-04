<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Promo;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function dashboard()
    {
        $orders = Order::all();
        $users = User::all();
        $promos = Promo::latest()->get();

        return view('admin.home', compact('orders', 'users', 'promos'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->status = $request->input('status');
        $order->save();

    return response()->json($order);
    }
}
