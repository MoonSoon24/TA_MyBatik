<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image')->store('designs', 'public');

        $design = new Design([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'image_path' => $path,
            'is_public' => true,
        ]);
        $design->save();

        return back()->with('success', 'Design uploaded successfully!');
    }

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

    public function addComment(Request $request, Design $design)
    {
        $request->validate(['body' => 'required|string']);

        $comment = $design->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);
        
        $comment->load('user');

        return response()->json(['comment' => $comment]);
    }

    // NEW: Update method
    public function update(Request $request, Design $design)
    {
        // Authorization: Ensure the logged-in user owns the design
        if (Auth::id() !== $design->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $design->title = $request->title;
        $design->save();

        return response()->json(['title' => $design->title]);
    }

    // NEW: Destroy method
    public function destroy(Design $design)
    {
        // Authorization: Ensure the logged-in user owns the design
        if (Auth::id() !== $design->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the image file from storage
        Storage::disk('public')->delete($design->image_path);

        // Delete the design record from the database
        $design->delete();

        return response()->json(['success' => 'Design deleted successfully.']);
    }
}
