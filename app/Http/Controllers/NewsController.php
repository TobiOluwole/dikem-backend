<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Services\NewsService;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getAllNews(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->newsService->getAllNews($page, $perPage);
    }

    public function getNews($idOrSlug)
    {
        return $this->newsService->getNews($idOrSlug);
    }

    public function createNews(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:news,slug',
            'title' => 'required',
            'content' => 'required',
            'visible' => 'required | boolean',
            'tags' => 'nullable',
            'images' => 'nullable',
        ]);
        return $this->newsService->createNews($request->only('title', 'content', 'tags', 'images', 'slug', 'visible'));
    }

    public function editNews(Request $request, $idOrSlug)
    {
        $request->validate([
            'slug' => ['nullable', Rule::unique('news', 'slug')->ignore($request->only('id')['id'])],
            'title' => 'nullable',
            'content' => 'nullable',
            'tags' => 'nullable',
            'images' => 'nullable',
            'visible' => 'nullable|boolean',
        ]);
        return $this->newsService->editNews($request->only('content', 'tags', 'slug', 'title', 'images'), $idOrSlug);
    }

    public function deleteNews($idOrSlug)
    {
        return $this->newsService->deleteNews($idOrSlug);
    }

    public function searchNews(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        return $this->newsService->searchNews($query, $page, $perPage);
    }

    public function getNewsByTag($tag)
    {
        return $this->newsService->getNewsByTag($tag);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        return $this->newsService->uploadImage($request->file('file'));
    }

    public function deleteImage($link)
    {
        return $this->newsService->deleteImage($link);
    }
}
