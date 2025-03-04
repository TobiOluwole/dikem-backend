<?php

namespace App\Http\Controllers;

use App\Http\Services\SocialsService;
use Illuminate\Http\Request;

class SocialsController extends Controller {

    private $socialsService;

    public function __construct(SocialsService $socialsService )
    {
        $this->socialsService = $socialsService;
    }

    public function getSocials()
    {
        return $this->socialsService->getSocials();
    }

    public function updateSocials(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable',
            'whatsapp' => 'nullable',
            'phone' => 'nullable',
            'x' => 'nullable',
            'instagram' => 'nullable',
            'email' => 'nullable',
            'linkedin' => 'nullable',
            'logo' => 'nullable',
        ]);
        return $this->socialsService->updateSocials(
            $request->only(['facebook', 'whatsapp', 'phone', 'x', 'instagram', 'email', 'linkedin', 'logo'])
        );
    }

};
