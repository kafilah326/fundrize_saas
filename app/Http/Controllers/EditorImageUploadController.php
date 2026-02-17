<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditorImageUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
        ]);

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Store in 'public/editor-images'
                $path = $file->storeAs('editor-images', $filename, 'public');

                if (!$path) {
                    return response()->json(['error' => 'Gagal menyimpan file.'], 500);
                }

                // Return the URL
                return response()->json([
                    'url' => Storage::url($path)
                ]);
            }

            return response()->json(['error' => 'No image uploaded'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Upload gagal: ' . $e->getMessage()], 500);
        }
    }
}
