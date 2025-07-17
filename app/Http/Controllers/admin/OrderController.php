<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|string|in:Pending,Hold,Cancelled,In Progress,Ready,Completed',
        'nota' => 'nullable|string',
        'send_notification' => 'sometimes|boolean',
        'notification_title' => 'required_if:send_notification,true|string|max:255',
        'notification_message' => 'required_if:send_notification,true|string|max:1000',
    ]);

    $order = Order::findOrFail($id);
    $order->status = $validated['status'];
    if (isset($validated['nota'])) {
        $order->nota = $validated['nota'];
    }
    $order->save();

    if ($request->input('send_notification', false)) {
        Notification::create([
            'user_id' => $order->id_user,
            'order_id' => $order->id_pesanan,
            'title' => $validated['notification_title'],
            'message' => $validated['notification_message'],
        ]);
    }

    return response()->json($order->fresh());
}

    public function getUserOrders($userId)
    {
        $userIdArray = explode(',', $userId);

        $orders = Order::whereIn('id_user', $userIdArray)->latest()->get(); 
        
        return response()->json($orders);
    }
}
