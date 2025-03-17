<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Http\Request;
use App\Http\Services\PagesService;

class PagesController extends Controller
{
    private $pagesService;

    public function __construct(PagesService $pagesService)
    {
        $this->pagesService = $pagesService;
    }

    public function getAllPages()
    {
        return $this->pagesService->getAllPages();
    }

    public function getPage($idOrSlug)
    {
        return $this->pagesService->getProject($idOrSlug);
    }

    public function createPage(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:news,slug',
            'name' => 'required|unique:news,name|string',
            'parent_id' => 'integer|exists:pages,id|nullable',
        ]);
        return $this->pagesService->createPage($request->only('name', 'parent', 'slug'));
    }

    public function editPage(Request $request, $idOrSlug)
    {
        $request->validate([
            'slug' => 'nullable|unique:news,slug',
            'name' => 'nullable|unique:news,name|string',
            'parent_id' => 'integer|exists:pages,id|nullable',
        ]);
        return $this->pagesService->editPage($request->only('name', 'parent', 'slug'), $idOrSlug);
    }

    public function deletePage($idOrSlug)
    {
        return $this->pagesService->deletePage($idOrSlug);
    }
}
