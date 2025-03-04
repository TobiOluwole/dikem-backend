<?php

namespace App\Http\Services;

use App\Models\Announcement;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class AnnouncementService
{
    public function getAllAnnouncements($page = 1, $perPage = 10)
    {
        return Announcement::orderBy('created_at', 'desc')->with('user')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getAnnouncement($idOrSlug)
    {
        return Announcement::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
    }

    public function createAnnouncement($data)
    {
        $data['user_id'] = JWTAuth::user()->id;
        $announcement = Announcement::create($data);
        if (isset($data['image'])) {
            $this->updateAnnouncementImage($data['image'], $announcement->id);
        }
        return $announcement;
    }

    public function updateAnnouncement($data, $idOrSlug)
    {
        $announcement = Announcement::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$announcement) {
            return response()->json(null, 404);
        }
        $announcement->update($data);

        if (isset($data['image'])) {
            $this->updateAnnouncementImage($data['image'], $idOrSlug);
        }

        return $announcement;
    }

    public function updateAnnouncementImage($base64Image, $idOrSlug)
    {
        // Retrieve the announcement by ID or slug
        $announcement = Announcement::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();

        if (!$announcement) {
            return response()->json(['error' => 'Announcement not found.'], 404);
        }

        // Define the storage path
        $storagePath = 'announcements/' . $announcement->id . '.jpg';

        // If no image is provided, delete the existing image
        if (is_null($base64Image)) {
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
            return response()->json(['message' => 'Image deleted successfully.'], 200);
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
            $storagePath = 'announcements/' . $announcement->id . '.' . $imageType;
            Storage::disk('public')->put($storagePath, $imageData);

            return response()->json(['message' => 'Image updated successfully.'], 200);
        } else {
            return response()->json(['error' => 'Invalid base64 image format.'], 400);
        }
    }

    public function deleteAnnouncement($idOrSlug) 
    {
        $announcement = Announcement::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$announcement) {
            return response()->json(null, 404);
        }
        $announcement->delete();
        return response()->json(null, 200);
    }

    public function searchAnnouncement($query, $page = 1, $perPage = 10)
    {
        return Announcement::where('title', 'like', '%' . $query . '%') ->orWhere('slug', 'like', '%' . $query . '%')->with('user')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
    }
}