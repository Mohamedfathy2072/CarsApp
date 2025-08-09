<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::all();

        $items = $partners->mapWithKeys(function ($partner) {
            return [
                $partner->id => [
                    'id' => $partner->id,
                    'title' => $partner->title,
                    'description' => $partner->description,
                    'link' => $partner->link,
                    'image_url' => asset('storage/' . $partner->image),
                    'created_at' => $partner->created_at,
                    'updated_at' => $partner->updated_at,
                ]
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Partners fetched successfully.',
            'data' => [
                'items' => $items,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'image' => 'required|image|max:2048',
        ]);

        $imagePath = $request->file('image')->store('partners', 'public');

        $partner = Partner::create([
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'image' => $imagePath,
        ]);

        return response()->json($partner, 201);
    }

    public function show($id)
    {
        $partner = Partner::findOrFail($id);
        return response()->json($partner);
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($partner->image);
            $partner->image = $request->file('image')->store('partners', 'public');
        }

        $partner->update($request->only(['title', 'description', 'link', 'is_active']));

        return response()->json($partner);
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        Storage::disk('public')->delete($partner->image);
        $partner->delete();

        return response()->json(['message' => 'Partner deleted successfully']);
    }
}
