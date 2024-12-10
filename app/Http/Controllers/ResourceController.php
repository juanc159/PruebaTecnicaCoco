<?php

namespace App\Http\Controllers;

use App\Constants\MessagesAlert;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resource\ResourceListResource;
use App\Repositories\ResourceRepository;

class ResourceController extends Controller
{
    public function __construct(
        protected ResourceRepository $resourceRepository,
    ) {}

    public function list()
    {
        try {
            $resources = $this->resourceRepository->list();
            $resources =  ResourceListResource::collection($resources);

            return response()->json($resources);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => MessagesAlert::API_ERROR
            ]);
        }
    }

    public function availability($id)
    {
        try {
            $this->resourceRepository->findOrFail($id);
            return response()->json(['available' => true]);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => MessagesAlert::API_ERROR
            ]);
        }
    }
}
