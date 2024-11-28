<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home');
    }
}
