<?php

namespace App\Http\Controllers;

use App\Http\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function getAllEvents(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->eventService->getAllEvents($page, $perPage);
    }

    public function getEvent($idOrSlug)
    {
        return $this->eventService->getEvent($idOrSlug);
    }

    public function createEvent(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:events,slug',
            'title' => 'required',
            'content' => 'required',
            'datetime' => 'required',
            'type' => 'required',
            'images' => 'nullable',
        ]);
        return $this->eventService->createEvent($request->only('title', 'content', 'datetime', 'type', 'images', 'slug'));
    }

    public function updateEvent(Request $request, $idOrSlug)
    {
        $request->validate([
            'slug' => ['nullable', Rule::unique('events', 'slug')->ignore($request->only('id')['id'])],
            'title' => '',
            'content' => '',
            'datetime' => '',
            'type' => '',
            'images' => 'nullable',
        ]);
        $data = $request->only('title', 'content', 'datetime', 'type', 'images', 'slug');
        return $this->eventService->updateEvent($data, $idOrSlug);
    }

    public function deleteEvent($idOrSlug)
    {
        return $this->eventService->deleteEvent($idOrSlug);
    }

    public function searchEvent(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->eventService->searchEvent($query, $page, $perPage);
    }


    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
        ]);
        return $this->eventService->uploadImage($request->file('file'));
    }

    public function deleteImage($link)
    {
        return $this->eventService->deleteImage($link);
    }
}



