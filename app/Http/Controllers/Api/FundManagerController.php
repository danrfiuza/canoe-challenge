<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateFundManagerRequest;
use App\Http\Requests\Api\UpdateFundRequest;
use App\Http\Resources\FundManagerResource;
use App\Models\FundManager;
use App\Services\FundManagerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FundManagerController extends Controller
{
    public function __construct(private FundManagerService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => FundManagerResource::collection($this->service->index($request->all()))->response()->getData()
        ])->setStatusCode(200);
    }

    public function store(CreateFundManagerRequest $request): JsonResponse
    {
        $resource = new FundManagerResource($this->service->create($request->validated()));

        return response()->json([
            'message' => 'Fund Manager created successfully',
            'data' => $resource
        ])->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFundRequest $request, FundManager $fundManager): JsonResponse
    {
        $resource = new FundManagerResource($this->service->update($fundManager, $request->validated()));

        return response()->json([
            'message' => 'Fund Manager updated successfully',
            'data' => $resource
        ])->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FundManager $fundManager): JsonResponse
    {
        return response()->json([
            'message' => 'Fund Manager Deleted successfully',
            'data' => $this->service->delete($fundManager)
        ])->setStatusCode(200);
    }
}
