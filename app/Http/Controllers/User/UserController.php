<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Service\ImageHandler\ImageUploadService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public ImageUploadService $imageUploadService;


    public function __construct(ImageUploadService $imageUpload){
        $this->imageUploadService = $imageUpload;
    }


    public function store(Request $request){

        $imagePath = $this->imageUploadService->saveImageFromUrl($request->image,'images');

        return $imagePath;
    }
}
