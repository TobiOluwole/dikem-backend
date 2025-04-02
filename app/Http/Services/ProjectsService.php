<?php

namespace App\Http\Services;

use App\Models\Projects;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectsService
{
    public function getAllProjects($page = 1, $perPage = 10)
    {
        return Projects::with('user')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getProject($idOrSlug)
    {
        return Projects::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
    }

    public function createProject($data)
    {
        $data['user_id'] = JWTAuth::user()->id;
        return Projects::create($data);
    }

    public function editProject($data, $idOrSlug)
    {
        $project = Projects::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$project) {
            return response()->json(null, 404);
        }
        $project->update($data);
        return $project;
    }

    public function deleteProject($idOrSlug)
    {
        $project = Projects::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$project) {
            return response()->json(null, 404);
        }
        $project->delete();
        return response()->json(null, 200);
    }

    public function searchProjects($query, $page = 1, $perPage = 10)
    {
        return Projects::where('title', 'like', '%' . $query . '%') ->orWhere('slug', 'like', '%' . $query . '%')->orderBy('created_at', 'desc')->with('user')->paginate($perPage, ['*'], 'page', $page);
    }

    public function uploadImage($image)
    {
        $image->store('projects', 'public');
        return response()->json(['link' => Storage::url('projects/' . $image->hashName())], 200);
    }

    public function deleteImage($link)
    {
        return Storage::disk('public')->delete("projects/$link");
    }
}
