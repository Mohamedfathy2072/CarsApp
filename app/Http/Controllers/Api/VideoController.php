<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
{
    $videos = \App\Models\Video::all();

    $items = $videos->mapWithKeys(function ($video) {
        return [
            $video->id => [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description,
                'video_url' => $video->video,
                'created_at' => $video->created_at,
                'updated_at' => $video->updated_at,
            ]
        ];
    });

    return response()->json([
        'status' => true,
        'message' => 'Videos fetched successfully.',
        'data' => [
            'items' => $items
        ]
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:50000', // Max ~50MB
        ]);

        $videoPath = $request->file('video')->store('videos', 'public');

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'video' => $videoPath,
        ]);

        return response()->json($video, 201);
    }

    public function show($id)
    {
        return response()->json(Video::findOrFail($id));
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        Storage::disk('public')->delete($video->video);
        $video->delete();

        return response()->json(['message' => 'Video deleted successfully']);
    }

}

