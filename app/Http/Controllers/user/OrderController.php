<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\RiwayatPesanan;

class OrderController extends Controller
{
    // store size and design data to session
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'size' => 'required|in:XS,S,M,L,XL',
            'design_file' => 'required|image|mimes:png|max:2048', // 2MB Max
        ]);

        $designPath = $request->file('design_file')->store('designs', 'public');

        $request->session()->put('order_details', [
            'ukuran' => $validatedData['size'],
            'desain' => $designPath,
        ]);

        return redirect()->route('checkout.show');
    }

    // show the checkout page with data from session
    public function showCheckout(Request $request)
    {
        if (!$request->session()->has('order_details')) {
            return redirect()->route('ukuran')->with('error', 'Please select a size and design first.');
        }

        $orderDetails = $request->session()->get('order_details');
        $imagePath = $orderDetails['desain'];

        $imageUrl = asset('storage/' . $imagePath);

        return view('user.checkout', [
            'uploadedBatikUrl' => $imageUrl
        ]);
    }

    // store the order in the database, create riwayat and clear session data
    public function storeOrder(Request $request)
    {
        $orderDetails = $request->session()->get('order_details');
        if (!$orderDetails) {
            return redirect()->route('ukuran')->with('error', 'Your session has expired. Please start again.');
        }

        $validatedCheckoutData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'street_address' => 'required|string|max:500',
            'phone' => 'required|string|max:15',
            'payment_method' => 'required|in:bank_transfer,qris',
            'additional_note' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        $order = Order::create([
            'id_user'        => $user->id,
            'email'          => $user->email,
            'nama'           => $validatedCheckoutData['first_name'] . ' ' . $validatedCheckoutData['last_name'],
            'alamat'         => $validatedCheckoutData['street_address'],
            'no_telepon'     => $validatedCheckoutData['phone'],
            'ukuran'         => $orderDetails['ukuran'],
            'desain'         => $orderDetails['desain'],
            'metode_bayar'   => $validatedCheckoutData['payment_method'],
            'tanggal_pesan'  => now(),
            'status'         => 'Pending',
            'nota'           => $validatedCheckoutData['additional_note'],
            'total'          => 300000,
        ]);

        RiwayatPesanan::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id_pesanan,
                ]);

        $request->session()->forget('order_details');

        return redirect()->route('receipt', ['order' => $order->id_pesanan])
                         ->with('success', 'Your order has been placed successfully!');
    }

    // show receipt
    public function showReceipt(Order $order)
    {
        if (auth()->id() !== $order->id_user) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.receipt', compact('order'));
    }
}
