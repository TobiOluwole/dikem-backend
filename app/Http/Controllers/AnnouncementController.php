<?php

namespace App\Http\Controllers;

use App\Http\Services\AnnouncementService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    private $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function getAllAnnouncements(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->announcementService->getAllAnnouncements($page, $perPage);
    }

    public function getAnnouncement($idOrSlug)
    {
        return $this->announcementService->getAnnouncement($idOrSlug);
    }

    public function createAnnouncement(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:announcements,slug',
            'title' => 'required',
            'content' => 'required',
            'visible' => 'boolean',
            'images' => 'nullable',
        ]);
        return $this->announcementService->createAnnouncement($request->only('title', 'content', 'visible', 'images', 'slug'));
    }

    public function updateAnnouncement(Request $request, $idOrSlug)
    {
        $request->validate([
            'slug' => ['nullable', Rule::unique('announcements', 'slug')->ignore($request->only('id')['id'])],
            'title' => '',
            'content' => '',
            'visible' => 'boolean',
            'images' => 'nullable',
        ]);
        $data = $request->only('title', 'content', 'visible', 'images', 'slug');
        return $this->announcementService->updateAnnouncement($data, $idOrSlug);
    }

    public function deleteAnnouncement($idOrSlug)
    {
        return $this->announcementService->deleteAnnouncement($idOrSlug);
    }

    public function searchAnnouncement(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->announcementService->searchAnnouncement($query, $page, $perPage);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
        ]);
        return $this->announcementService->uploadImage($request->file('file'));
    }

    public function deleteImage($link)
    {
        return $this->announcementService->deleteImage($link);
    }
}



