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
        $disk = 'html';

        if(isset($media->format_id) || isset($media->src_id)) {
            $disk = 'template';
        }

        $path = Storage::disk($disk)->path($media->path);

        return response()->download($path, $media->name, [
            'Content-Type' => $media->mime_type,
        ]);
    }
}
