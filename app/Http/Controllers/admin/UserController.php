<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Promo;
use Illuminate\Http\Request;
use Carbon\Carbon;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $orders = Order::all();
        $promos = Promo::latest()->get();

        return view('admin.home', compact('orders','users','promos'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        return redirect()->route('admin.home')->with('success', 'User details updated successfully.');
    }

    public function verify(string $id)
    {
        $user = User::findOrFail($id);
        
        $message = '';

        if ($user->email_verified_at) {
            $user->forceFill([
                'email_verified_at' => null
            ])->save();
            $message = 'User has been successfully unverified.';
        } else {
            $user->forceFill([
                'email_verified_at' => Carbon::now()
            ])->save();
            $message = 'User has been successfully verified.';
        }

        return redirect()->route('admin.home')->with('success', $message);
    }


    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.home')->with('success', 'User has been successfully deleted.');
    }
}
