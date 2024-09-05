<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileDownloadController extends Controller
{
    public function download(Request $request, $filename)
    {
        // Check if the URL is signed
        if (!$request->hasValidSignature()) {
            abort(Response::HTTP_FORBIDDEN, 'Invalid signature.');
        }

        // Serve the file
        $path = 'exports/' . $filename;
        if (!Storage::exists($path)) {
            abort(Response::HTTP_NOT_FOUND, 'File not found.');
        }

        return Storage::download($path);
    }
}
