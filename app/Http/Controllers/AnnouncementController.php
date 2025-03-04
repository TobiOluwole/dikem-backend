<?php

namespace App\Http\Controllers;

use App\Http\Services\AnnouncementService;
use Illuminate\Http\Request;

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
            'image' => 'nullable',
        ]);
        return $this->announcementService->createAnnouncement($request->only('title', 'content', 'visible', 'image', 'slug'));
    }

    public function updateAnnouncement(Request $request, $idOrSlug)
    {
        $request->validate([
            'slug' => 'unique:announcements,slug',
            'title' => '',
            'content' => '',
            'visible' => 'boolean',
            'image' => 'nullable',
        ]);
        $data = $request->only('title', 'content', 'visible', 'image', 'slug');
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
}



