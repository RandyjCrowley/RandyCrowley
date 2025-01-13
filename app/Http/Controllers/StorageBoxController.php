<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\StorageBox;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StorageBoxController extends Controller
{
    public function index(): View
    {
        return view('storage-box');
    }

    public function show(StorageBox $storageBox): View
    {
        return view('storage-box-show', [
            'box' => $storageBox,
        ]);
    }

    public function qr(StorageBox $storageBox): Response
    {
        $url = route('open-box', [$storageBox->slug]);

        $image = QrCode::format('png')->size(300)->generate($url);

        return response($image)->header('Content-type', 'image/png');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'box_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'box_photos' => 'required|array',
        ]);

        // Save photos to storage
        DB::beginTransaction();
        $storageBox = $request->user()->storageBoxes()->create([
            'box_name' => $request->box_name,
            'description' => $request->description,
        ]);

        $saved_photos = $this->savePhotos($request->box_photos, $storageBox->id);

        $storageBox->photos()->createMany($saved_photos);

        DB::commit();

        return response()->json(['success' => true, 'redirect' => route('storage-qr', $storageBox->slug)]);
    }

    // Gets passed an array of photos and returns paths to the photos
    public function savePhotos(array $box_photos, int $storageBoxId): array
    {
        $saved_photos = [];
        $base_path = 'qr_codes/' . $storageBoxId . '/';
        foreach ($box_photos as $photo) {
            $path = $base_path . Str::uuid7() . '.' . $photo->getClientOriginalExtension();
            Storage::disk('public')->put($path, $photo->getContent());
            $saved_photos[]['photo_path'] = $path;
        }

        return $saved_photos;
    }
}
