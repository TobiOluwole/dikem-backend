<?php

namespace App\Http\Services;

use App\Models\Announcement;
use App\Models\Events;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class EventService
{
    public function getAllEvents($page = 1, $perPage = 10)
    {
        return Events::orderBy('created_at', 'desc')->with('user')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getEvent($idOrSlug)
    {
        return Events::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
    }

    public function createEvent($data)
    {
        $data['user_id'] = JWTAuth::user()->id;
        $event = Events::create($data);
//        if (isset($data['image'])) {
//            $this->updateEventImage($data['image'], $event->id);
//        }
        return $event;
    }

    public function updateEvent($data, $idOrSlug)
    {
        $event = Events::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$event) {
            return response()->json(null, 404);
        }
        $event->update($data);

//        if (isset($data['image'])) {
//            $this->updateEventImage($data['image'], $idOrSlug);
//        }

        return $event;
    }

    public function updateEventImage($base64Image, $idOrSlug)
    {
        // Retrieve the announcement by ID or slug
        $event = Events::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();

        if (!$event) {
            return response()->json(null, 404);
        }

        // Define the storage path
        $storagePath = 'events/' . $event->id . '.jpg';

        // If no image is provided, delete the existing image
        if (is_null($base64Image)) {
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
            return response()->json(null, 200);
        }

        // Validate and process the base64 image
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageType = strtolower($matches[1]); // Extract image type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']; // Define allowed image types

            if (!in_array($imageType, $allowedTypes)) {
                return response()->json(['error' => 'Invalid image type.'], 400);
            }

            // Remove the base64 header to get the raw image data
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($base64Image);

            if ($imageData === false) {
                return response()->json(['error' => 'Base64 decoding failed.'], 400);
            }

            // Store the image
            $storagePath = 'events/' . $event->id . '.' . $imageType;
            Storage::disk('public')->put($storagePath, $imageData);

            return response()->json(null, 200);
        } else {
            return response()->json(['error' => 'Invalid base64 image format.'], 400);
        }
    }

    public function deleteEvent($idOrSlug)
    {
        $event = Events::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$event) {
            return response()->json(null, 404);
        }
        $event->delete();
        return response()->json(null, 200);
    }

    public function searchEvent($query, $page = 1, $perPage = 10)
    {
        return Events::where('title', 'like', '%' . $query . '%') ->orWhere('slug', 'like', '%' . $query . '%')->with('user')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
    }

    public function uploadImage($image)
    {
        $image->store('announcements', 'public');
        return response()->json(['link' => Storage::url('announcements/' . $image->hashName())], 200);
    }

    public function deleteImage($link)
    {
        return Storage::disk('public')->delete("announcements/$link");
    }
}
