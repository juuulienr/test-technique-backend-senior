<?php

namespace App\UI\Http\Controllers\Api\Public;

use App\Application\Services\ProfileApplicationService;
use App\UI\Http\Controllers\Controller;
use App\UI\Http\Resources\ProfileResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileApplicationService $profileApplicationService
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        $profils = $this->profileApplicationService->getActiveProfiles();

        return ProfileResource::collection($profils);
    }
}
