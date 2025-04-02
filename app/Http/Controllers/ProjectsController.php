<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;
use App\Http\Services\ProjectsService;

class ProjectsController extends Controller
{
    private $projectsService;

    public function __construct(ProjectsService $projectsService)
    {
        $this->projectsService = $projectsService;
    }

    public function getAllProjects(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->projectsService->getAllProjects($page, $perPage);
    }

    public function getProject($idOrSlug)
    {
        return $this->projectsService->getProject($idOrSlug);
    }

    public function createProject(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:projects,slug',
            'title' => 'required',
            'tags' => 'nullable',
            'completed' => 'required|boolean',
            'content' => 'nullable',
            'images' => 'nullable',
        ]);
        return $this->projectsService->createProject($request->only('title', 'content', 'completed', 'images', 'slug', 'tags'));
    }

    public function editProject(Request $request, $idOrSlug)
    {
        $request->validate([
            'slug' => 'nullable|unique:news,slug',
            'title' => 'nullable',
            'content' => 'nullable',
            'completed' => 'nullable|boolean',
            'images' => 'nullable',
        ]);
        return $this->projectsService->editProject($request->only('content', 'completed', 'slug', 'title', 'images'), $idOrSlug);
    }

    public function deleteProject($idOrSlug)
    {
        return $this->projectsService->deleteProject($idOrSlug);
    }

    public function searchProject(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->projectsService->searchProjects($query, $page, $perPage);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
        ]);
        return $this->projectsService->uploadImage($request->file('file'));
    }

    public function deleteImage($link)
    {
        return $this->projectsService->deleteImage($link);
    }
}
