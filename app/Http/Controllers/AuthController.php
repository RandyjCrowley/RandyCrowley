<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): string
    {
        $imageData = $request->input('image'); // Get base64 image data
        $image = str_replace('data:image/jpeg;base64,', '', $imageData); // Remove the base64 prefix
        $image = str_replace(' ', '+', $image); // Replace spaces with plus sign
        $imageName = uniqid().'.jpg'; // Generate a unique name

        // Save image to storage
        Storage::put("public/images/{$imageName}", base64_decode($image));

        Auth::login(1);

        return route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
