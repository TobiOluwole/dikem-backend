<?php

namespace App\Http\Services;

use App\Models\Socials;
use Illuminate\Support\Facades\Storage;

class SocialsService
{
    public function getSocials()
    {
        return Socials::find(1);
    }

    public function updateSocials($data)
    {
        if($data['logo'])
        {
            $data['logo'] = $this->uploadImage($data['logo']);
        } else
        {
            $this->deleteImage();
        }
        return Socials::where('id', 1)->update($data);
    }


    public function uploadImage($image)
    {
//        return $image->storeAs('socials', "socials.$image->extension()", 'public');
        return $image->storeAs("logo.$image->extension()", 'public');
    }

    public function deleteImage()
    {
        $extensions = ['jpg', 'jpeg', 'png'];
        foreach ($extensions as $extension) {
            if (Storage::disk('public')->exists("logo.$extension")) {
                // Delete the file
                Storage::disk('public')->delete("logo.$extension");
                // Optionally, return a message indicating successful deletion
                return true;
            }
        }
        return false;
    }

}
