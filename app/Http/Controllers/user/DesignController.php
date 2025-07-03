<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    /**
     * Store a newly created design in storage and share it to the gallery.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Store the image in 'public/designs' which links to 'storage/app/public/designs'
        $path = $request->file('image')->store('designs', 'public');

        Auth::user()->designs()->create([
            'title' => $request->title,
            'image_path' => $path, // Store the relative path
            'is_public' => true,
        ]);

        return redirect()->route('gallery.index')->with('success', 'Design shared successfully!');
    }

    /**
     * Add a comment to a specific design.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(Request $request, Design $design)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // Create the comment
        $comment = $design->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        // Eager load the user relationship for the new comment
        $comment->load('user');

        // FIXED: Return the new comment as a JSON response for AJAX
        return response()->json([
            'comment' => $comment,
            'user' => $comment->user // Include user data for immediate display
        ]);
    }

    /**
     * Toggle a like on a specific design for the current user.
     *
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleLike(Design $design)
    {
        $user = Auth::user();
        $like = $design->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $design->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $design->likes()->count(),
        ]);
    }
}
