<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = collect([
            (object) ['photo' => asset('assets/img/a.png')],
            (object) ['photo' => asset('assets/img/a.png')],
            (object) ['photo' => asset('assets/img/a.png')],
        ]);

        return view('home', compact('banners'));
    }
}
