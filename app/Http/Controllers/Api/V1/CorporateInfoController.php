<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CorporateInfoResource;
use App\Models\CorporateInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CorporateInfoController extends BaseController
{
    /**
     * Display a listing of corporate info pages.
     */
    public function index(): AnonymousResourceCollection
    {
        $pages = CorporateInfo::active()
            ->ordered()
            ->get();

        return CorporateInfoResource::collection($pages);
    }

    /**
     * Display the specified corporate info by slug.
     */
    public function show(string $slug): CorporateInfoResource|JsonResponse
    {
        $page = CorporateInfo::findBySlug($slug);

        if (!$page) {
            return $this->notFound('Corporate info page not found');
        }

        return new CorporateInfoResource($page);
    }
}
