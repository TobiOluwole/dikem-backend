<?php

namespace App\Http\Services;

use App\Models\Pages;

class PagesService
{
    public function getAllPages()
    {
        return Pages::get();
    }

    public function getProject($idOrSlug)
    {
        return Pages::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
    }

    public function createPage($data)
    {
        return Pages::create($data);
    }

    public function editPage($data, $idOrSlug)
    {
        $page = Pages::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$page) {
            return response()->json(null, 404);
        }
        $page->update($data);
        return $page;
    }

    public function deletePage($idOrSlug)
    {
        $page = Pages::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->first();
        if (!$page) {
            return response()->json(null, 404);
        }
        $page->delete();
        return response()->json(null, 200);
    }
}
