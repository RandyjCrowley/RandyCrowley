<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ImagerController extends Controller
{
    public function index() : View
    {
        return view('imager');
    }

    public function initializeUpload(Request $request)
    {
        $uploadId = Str::uuid()->toString();

        // Create upload record
        FileUpload::create([
            'user_id' => $request->user()->id,
            'filename' => $uploadId,
            'original_filename' => $request->filename,
            'mime_type' => $request->mimeType,
            'file_size' => $request->fileSize,
            'path' => "uploads/temp/{$uploadId}",
            'status' => 'uploading',
        ]);

        // Create an empty file
        Storage::disk('local')->put("uploads/temp/{$uploadId}", '');

        // Store the upload ID in session with client upload ID as part of the key

        return response()->json(['uploadId' => $uploadId]);
    }

    public function uploadChunk(Request $request)
    {
        $uploadId = $request->uploadId;

        $path = Storage::disk('local')->path("uploads/temp/{$uploadId}");

        // Append chunk to file using native PHP file operations
        if ($handle = fopen($path, 'ab')) {
            fwrite($handle, $request->file('chunk')->get());
            fclose($handle);

            return response()->json(['success' => true]);
        }

        // Update status if failed
        $upload = FileUpload::where('filename', $uploadId)->first();
        $upload->update(['status' => 'failed']);

        return response()->json(['success' => false], 500);
    }

    public function finalizeUpload(Request $request)
    {
        $uploadId = $request->uploadId;

        if (Storage::disk('local')->exists("uploads/temp/{$uploadId}")) {
            $upload = FileUpload::where('filename', $uploadId)->first();

            // Move file to final location
            Storage::disk('local')->move(
                "uploads/temp/{$uploadId}",
                "uploads/final/{$upload->original_filename}"
            );

            // Update upload record

            $upload->update([
                'path' => "uploads/final/{$upload->original_filename}",
                'status' => 'completed',
            ]);

            return response()->json(['success' => true]);
        }

        // Update status if failed
        $upload = FileUpload::where('filename', $uploadId)->first();
        $upload->update(['status' => 'failed']);

        return response()->json(['success' => false], 500);
    }

    public function cancelUpload(Request $request)
    {
        $uploadId = $request->uploadId;
        if (Storage::disk('local')->exists("uploads/temp/{$uploadId}")) {

            // Delete the temporary file if it exists
            Storage::disk('local')->delete("uploads/temp/{$uploadId}");

            $upload = FileUpload::where('filename', $uploadId)->first();

            if ($upload) {
                $upload->update(['status' => 'cancelled']);
            }

            return response()->json(['success' => true]);

        }

        return response()->json(['success' => false], 500);
    }
}
