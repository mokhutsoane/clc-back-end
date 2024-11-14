<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use Illuminate\Support\Facades\Auth;
use App\Models\DamageImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class HouseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',

        ]);

        $house = House::create([
            'user_id' => Auth::user()->id,
            'address' => $request->input('address'),
            'description' => $request->input('description'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        return response()->json([
            'message' => 'House created successfully',
            'house' => $house,
        ], 201);
    }

    public function index()
    {
        $houses = House::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'houses' => $houses,
        ]);
    }

    public function show(Request $request)
    {
        $houseData = $request->validate([
            'house_id' => 'required',
        ]);
        $user = $request->user();

        $house = House::find($houseData['house_id']);

        if (!$house || $user->id != $house->user_id) {
            return response()->json([
                'message' => 'House Not Found',
            ], 404);
        }
        $damageImages = DamageImage::where('house_id', $houseData['house_id'])->get();
        return response()->json([
            'house' => $house,
            'images' => $damageImages,
        ], 200);
    }

    public function updateHouse(Request $request, $id)
    {
        $houseData =  $request->validate([
            'address' => 'string|max:255',
            'description' => 'string',
            'latitude' => 'string',
    'longitude'=>'string'
        ]);
        $user = $request->user();
        $house = House::find($id);
        if (!$house || $user->id != $house->user_id) {
            return response()->json([
                'message' => 'House Not Found',
            ], 404);
        }
        $house->update($houseData);
        return response()->json([
            'message' => 'House upaded successfully',
            'house' => $house,
        ], 200);
    }

    public function destroy(Request $request)
    {
        $houseData = $request->validate([
            'house_id' => 'required',
        ]);
        $user = $request->user();
        // $house = House::where('id', $houseData['house_id'])->first();
        $house = House::find($houseData['house_id']);

        if (!$house || $user->id != $house->user_id) {
            return response()->json([
                'message' => 'House Not Found',
            ], 404);
        }
        $damageImages = DamageImage::where('house_id', $houseData['house_id'])->get();
        $filePaths = $damageImages->map(function ($damageImage) {
            return str_replace('/storage/', '', $damageImage->image_url);
        })->toArray();
        Log::info('File paths for deletion:', $filePaths);

        if (!empty($filePaths)) {
            Storage::disk('public')->delete($filePaths);
        }
        $house->delete();

        return response()->json([
            'message' => 'House Deleted',
        ], 200);
    }
}
