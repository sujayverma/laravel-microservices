<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    //

    public function upload(ImageUploadRequest $request)
    {
        $file = $request->file('image');
        $name = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('/products');
        $file->move($destinationPath, $name);
        return response()->json(['image_url' => '/products/' . $name], 201);
    }
}
