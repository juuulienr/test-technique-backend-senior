<?php

namespace App\Http\Controllers\Api\Public;

use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        $profils = $this->profileRepository->findActiveProfiles();

        return ProfileResource::collection($profils);
    }
}
