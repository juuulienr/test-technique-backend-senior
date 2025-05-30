<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProfileController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $profils = Profile::where('statut', 'actif')->get();

        return ProfileResource::collection($profils);
    }
}
