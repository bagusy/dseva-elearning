<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->user()->hasRole(User::ROLE_USER_EMPLOYEE)) {
            return redirect('/training');
        }

        $videos = Video::where('tag', 'like', '%intro%')
            ->where('category', '<>', Video::CATEGORY_PRIVATE)
            ->whereSource(Video::SOURCE_WISTIA)
            ->get();

        return view('home', compact('videos'));
    }
}
