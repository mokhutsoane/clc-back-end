<?php

namespace App\Http\Controllers;

use App\Models\DamageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\House;
use Illuminate\Http\File;

class DamageImageController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'images' => 'required',
            'images.*' => 'file|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();
        $house = House::find($request->house_id);
        if (!$house || $user->id != $house->user_id) {
            return response()->json([
                'message' => 'House is Invalid',
            ], 400);
        }

        $damageImages = [];
        $imageFiles = $request->file('images');

        if (!is_array($imageFiles)) {
            $imageFiles = [$imageFiles];
        }

        foreach ($imageFiles as $imageFile) {
            $fileName = $imageFile->getClientOriginalName();
            $path = $imageFile->store('damage_images', 'public');
            $imageUrl = Storage::url($path);
            $damageImages[] = DamageImage::create([
                'user_id' => Auth::user()->id,
                'house_id' => $request->house_id,
                'description' => $request->input('description'),
                'title' => $fileName,
                'image_url' => $imageUrl,
            ]);
        }

        return response()->json([
            'message' => 'Damage images added successfully',
            'data' => $damageImages
        ], 201);
    }


    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(Request $request,string $id)
    
    {
        $damageImage = DamageImage::findOrFail($id);

        $user = $request->user();
        if (!$damageImage || $user->id != $damageImage->user_id) {
            return response()->json([
                'message' => 'Image is Invalid',
            ], 400);
        }

        $filePath = str_replace('/storage/', '', $damageImage->image_url);
        $fileDeleted = Storage::disk('public')->delete($filePath);
        if ($fileDeleted) {
            $damageImage->delete();
            return response()->json([
                'message' => 'Damage image and file deleted successfully.',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failed to delete the image file.',
            ], 400);
        }
    }
}
