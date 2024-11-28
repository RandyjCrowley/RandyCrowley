<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
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
    public function store(Request $request) : \Illuminate\Http\RedirectResponse
    {
        // Validate the incoming request to ensure image data is present
        $request->validate([
            'image' => 'required|string',
        ]);

        // Get base64 image data
        $imageData = $request->input('image');

        // Check if the base64 data contains the correct prefix and strip it
        if (strpos($imageData, 'data:image/jpeg;base64,') !== false) {
            $image = str_replace('data:image/jpeg;base64,', '', $imageData); // Remove the base64 prefix
            $image = str_replace(' ', '+', $image); // Replace spaces with plus sign
        }
        else {
            return response()->json(['error' => 'Invalid image data'], 400); // Handle invalid image format
        }

        // Generate a unique name for the image
        $imageName = uniqid() . '.jpg';

        // Save the image to the storage
        if (Storage::put("public/images/{$imageName}", base64_decode($image, true))) {
            // Log the user in (if needed)
            Auth::loginUsingId(1); // Login user as an example, ensure this is a valid operation

            // Redirect to the home route
            return Redirect::route('home');
        }
        else {
            return Redirect::back()->withErrors(['error' => 'Unable to save image']); // Handle error saving image
        }
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
