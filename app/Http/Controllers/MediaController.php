<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function download(Media $media) : BinaryFileResponse
    {
        $path = Storage::disk('html')->path($media->path);

        return response()->download($path, $media->name, [
            'Content-Type' => $media->mime_type,
        ]);
    }
}
