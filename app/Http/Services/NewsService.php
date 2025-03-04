<?php

namespace App\Http\Services;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class NewsService
{
    public function getAllNews($page = 1, $perPage = 10)
    {
        return News::with('user')->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getNews($idOrSlug)
    {
        return News::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
    }

    public function createNews($data)
    {
        $data['user_id'] = JWTAuth::user()->id;
        return News::create($data);
    }

    public function editNews($data, $idOrSlug)
    {
        $news = News::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();

        if (!$news) {
            return response()->json(null, 404);
        }

        $images = $news->images;
        foreach ($images as $image) {
            $this->deleteImage($image);
        }

        $news->update($data);
        return $news;
    }

    public function deleteNews($idOrSlug)
    {
        $news = News::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$news) {
            return response()->json(null, 404);
        }
        $news->delete();
        return response()->json(null, 200);
    }

    public function searchNews($query, $page = 1, $perPage = 10)
    {
        return News::where('title', 'like', '%' . $query . '%') ->orWhere('slug', 'like', '%' . $query . '%')->orderBy('created_at', 'desc')->with('user')->paginate($perPage, ['*'], 'page', $page);
    }

    public function getNewsByTag($tag)
    {
        return News::whereRaw("JSON_CONTAINS(tags, '\"$tag\"')")->orderBy('created_at', 'desc')->with('user')->paginate(10, ['*'], 'page', 1);
    }

    public function uploadImage($image)
    {
        $image->store('news', 'public');
        return Storage::url('news/' . $image->hashName());
    }

    public function deleteImage($link)
    {
        // return File::delete(Storage::url("news/" . $link));
        return Storage::disk('public')->delete('news/' . $link);
    }
}
