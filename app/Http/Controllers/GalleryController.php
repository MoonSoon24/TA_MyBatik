<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the public designs on the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $designs = \App\Models\Design::where('is_public', true)
                         ->with(['user', 'comments.user', 'likes'])
                         ->latest()
                         ->paginate(9);

        return view('gallery', compact('designs'));
    }
}