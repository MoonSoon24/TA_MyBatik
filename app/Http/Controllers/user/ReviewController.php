<?php

namespace App\Http\Controllers\User;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        // Load the reviews directly in this controller.
        $reviews = Review::with('user')
                         ->latest()
                         ->limit(3)
                         ->get();

        // Pass the $reviews variable to the 'home' view.
        return view('home', ['reviews' => $reviews]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|exists:orders,id_pesanan',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'id_pesanan' => $request->id_pesanan,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Thank you! Your review has been saved.');
    }
}