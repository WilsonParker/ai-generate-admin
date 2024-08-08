<?php

namespace App\Http\Controllers\Third;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Murdercode\TinymceEditor\Http\Controllers\TinyImageController;

class UploadController extends TinyImageController
{
    public function upload(Request $request): JsonResponse
    {
        $disk = config('filesystems.blog_disk');

        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        $imageFolder = config('nova-tinymce-editor.extra.upload_images.folder') ?? 'images';
        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            $file = Storage::disk($disk)->putFile($imageFolder, $temp['tmp_name']);

            return response()->json(['location' => Storage::disk($disk)->url($file)]);
        } else {
            return response()->json(['error' => 'Failed to move uploaded file.']);
        }

    }

}
