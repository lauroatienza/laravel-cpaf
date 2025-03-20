<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileViewController extends Controller
{
    public function show($directory, $filename)
    {
        // ✅ Define allowed directories
        $allowedDirectories = ['fsr_rsr_files']; 

        // ✅ Check if the directory is allowed
        if (!in_array($directory, $allowedDirectories)) {
            return response()->json(['error' => 'Invalid directory'], 403);
        }

        // ✅ Construct the correct file path
        $path = storage_path("app/public/{$directory}/{$filename}");

        // ✅ Check if the file exists
        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found: ' . $path], 404);
        }

        // ✅ Get the correct MIME type and return the file
        return response()->file($path, ['Content-Type' => mime_content_type($path)]);
    }
}
