<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Promo;
use App\Models\RiwayatPesanan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Exception;

class OrderController extends Controller
{
    public function applyPromo(Request $request)
    {
        $request->validate(['promo_code' => 'required|string']);
        $promo = Promo::where('code', $request->promo_code)->first();

        if (!$promo) {
            return back()->withErrors(['promo_code' => 'Invalid promo code.']);
        }
        if ($promo->expires_at && $promo->expires_at < now()) {
            return back()->withErrors(['promo_code' => 'This promo code has expired.']);
        }
        if ($promo->max_uses && $promo->current_uses >= $promo->max_uses) {
            return back()->withErrors(['promo_code' => 'This promo code has reached its usage limit.']);
        }

        Session::put('promo', [
            'code' => $promo->code,
            'type' => $promo->type,
            'value' => $promo->value
        ]);

        return back()->with('success', 'Promo code applied successfully!');
    }
    
    public function removePromo(Request $request)
    {
        $request->session()->forget('promo');
        return back()->with('success', 'Promo code removed.');
    }
    
    public function store(Request $request)
    {
        Log::info('OrderController@store called with request data: ', $request->all());
        // --- 1. Validation (remains the same) ---
        $validatedData = $request->validate([
        'size' => 'required|in:XS,S,M,L,XL,custom',
        'design_file' => 'required|image|mimes:png,jpeg,jpg|max:2048',
        'garment_type' => 'required|in:shirt,dress',

        // --- Corrected Shirt Custom Measurements ---
        'custom_body_length' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'shirt'),
            'nullable', 'numeric', 'min:1'
        ],
        'custom_sleeve_length' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'shirt'),
            'nullable', 'numeric', 'min:1'
        ],
        'custom_shoulder_width' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'shirt'),
            'nullable', 'numeric', 'min:1'
        ],
        'custom_body_width' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'shirt'),
            'nullable', 'numeric', 'min:1'
        ],
        'custom_neck_size' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'shirt'),
            'nullable', 'numeric', 'min:1'
        ],

        // --- Corrected Dress Custom Measurements ---
        'custom_dress_body_length' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'dress'),
            'nullable', 'numeric', 'min:1'
        ],
        'custom_dress_sleeve_length' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'dress'),
            'nullable', 'numeric', 'min:1'
        ],
        'custom_dress_shoulder_width' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'dress'),
            'nullable', 'numeric', 'min:1'
        ],
        'custom_dress_body_width' => [
            Rule::requiredIf(fn() => $request->input('size') === 'custom' && $request->input('garment_type') === 'dress'),
            'nullable', 'numeric', 'min:1'
        ],
    ]);

        // --- 2. Process Size Information ---
        $sizeDetails = ''; // Initialize the variable for the final string

        if ($validatedData['size'] === 'custom') {
            // If the size is custom, build the detailed string
            $sizeDetails = 'custom: ';
            $measurements = [];

            if ($validatedData['garment_type'] === 'shirt') {
                // For a shirt, use the five shirt measurements
                $measurements = [
                    'bl:' . $validatedData['custom_body_length'],
                    'sl:' . $validatedData['custom_sleeve_length'],
                    'sw:' . $validatedData['custom_shoulder_width'],
                    'bw:' . $validatedData['custom_body_width'],
                    'ns:' . $validatedData['custom_neck_size'],
                ];
            } else { // Otherwise, it's a dress
                // For a dress, use the four dress measurements
                $measurements = [
                    'bl:' . $validatedData['custom_dress_body_length'],
                    'sl:' . $validatedData['custom_dress_sleeve_length'],
                    'sw:' . $validatedData['custom_dress_shoulder_width'],
                    'bw:' . $validatedData['custom_dress_body_width'],
                ];
            }
            // Join the parts into a single string: "bl:70, sl:70, ..."
            $sizeDetails .= implode(', ', $measurements);
        } else {
            // If it's a standard size, just use the value directly (e.g., "XS", "S")
            $sizeDetails = $validatedData['size'];
        }

        // --- 3. Store File and Session Data ---
        $designPath = $request->file('design_file')->store('designs', 'public');

        $request->session()->put('order_details', [
            // Use the newly created $sizeDetails string here
            'ukuran' => $sizeDetails,
            'desain' => $designPath,
        ]);

        // Optional: Log the session data to check if it's correct
Log::info('Order details saved to session: ', $request->session()->get('order_details'));

        return redirect()->route('checkout.show');
    }
    
    public function showCheckout(Request $request)
    {
        if (!$request->session()->has('order_details')) {
            return redirect()->route('ukuran')->with('error', 'Please select a size and design first.');
        }

        $orderDetails = $request->session()->get('order_details');
        $imageUrl = asset('storage/' . $orderDetails['desain']);
        $basePrice = 300000;
        $promoDetails = $this->getPromoDetails($basePrice);

        return view('user.checkout', [
            'uploadedBatikUrl' => $imageUrl,
            'basePrice'        => $basePrice,
            'discountAmount'   => $promoDetails['discountAmount'],
            'finalPrice'       => $promoDetails['finalPrice'],
        ]);
    }

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
            'phone' => 'required|string|min:10|max:15',
            'payment_method' => 'required|in:bank_transfer,qris',
            'additional_note' => 'nullable|string|max:1000',
        ]);

        $basePrice = 300000;
        $promoDetails = $this->getPromoDetails($basePrice);
        
        $user = Auth::user();
        $order = null;

        try {
            DB::transaction(function () use ($validatedCheckoutData, $orderDetails, $user, &$order, $promoDetails) {
                $order = Order::create([
                    'id_user'       => $user->id,
                    'email'         => $user->email,
                    'nama'          => $validatedCheckoutData['first_name'] . ' ' . $validatedCheckoutData['last_name'],
                    'alamat'        => $validatedCheckoutData['street_address'],
                    'no_telepon'    => $validatedCheckoutData['phone'],
                    'ukuran'        => $orderDetails['ukuran'],
                    'desain'        => $orderDetails['desain'],
                    'metode_bayar'  => $validatedCheckoutData['payment_method'],
                    'tanggal_pesan' => now(),
                    'status'        => 'Pending',
                    'nota'          => $validatedCheckoutData['additional_note'],
                    'total'         => $promoDetails['finalPrice'],
                    'promo_code'    => $promoDetails['promoCodeUsed'],
                    'discount_amount' => $promoDetails['discountAmount'],
                ]);

                RiwayatPesanan::create([
                    'user_id'  => $user->id,
                    'order_id' => $order->id_pesanan, 
                ]);
                
                if ($promoDetails['promoCodeUsed']) {
                    $promo = Promo::where('code', $promoDetails['promoCodeUsed'])->first();
                    if ($promo) {
                        $promo->increment('current_uses');
                    }
                }
            });
        } catch (Exception $e) {
            return back()->with('error', 'An unexpected error occurred while placing your order. Please try again.')->withInput();
        }

        $request->session()->forget(['order_details', 'promo']);

        return redirect()->route('receipt', ['order' => $order->id_pesanan])
                         ->with('success', 'Your order has been placed successfully!');
    }
    
    public function showReceipt(Order $order)
    {
        if (auth()->id() !== $order->id_user) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.receipt', compact('order'));
    }
    
    private function getPromoDetails(float $basePrice): array
    {
        $finalPrice = $basePrice;
        $discountAmount = 0;
        $promoCodeUsed = null;

        if (Session::has('promo')) {
            $promoData = Session::get('promo');
            
            if ($promoData['type'] === 'fixed') {
                $discountAmount = $promoData['value'];
            } elseif ($promoData['type'] === 'percentage') {
                $discountAmount = ($basePrice * $promoData['value']) / 100;
            }
            
            $finalPrice = max(0, $basePrice - $discountAmount);
            $promoCodeUsed = $promoData['code'];
        }

        return [
            'finalPrice'      => $finalPrice,
            'discountAmount'  => $discountAmount,
            'promoCodeUsed'   => $promoCodeUsed,
        ];
    }
}