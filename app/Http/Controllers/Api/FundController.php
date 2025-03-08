<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateFundRequest;
use App\Http\Requests\Api\UpdateFundRequest;
use App\Http\Resources\FundResource;
use App\Models\Fund;
use Illuminate\Http\Request;

use App\Services\FundService;
use Illuminate\Http\JsonResponse;

class FundController extends Controller
{
    public function __construct(private FundService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => FundResource::collection($this->service->index($request->all()))->response()->getData()
        ])->setStatusCode(200);
    }

    public function show(Fund $fund): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => FundResource::make($fund)
        ])->setStatusCode(200);
    }

    public function store(CreateFundRequest $request): JsonResponse
    {
        $resource = new FundResource($this->service->create($request->validated()));

        return response()->json([
            'message' => 'Fund created successfully',
            'data' => $resource
        ])->setStatusCode(201);
    }

    public function update(UpdateFundRequest $request, Fund $fund)
    {
        $resource = new FundResource($this->service->update($fund, $request->validated()));

        return response()->json([
            'message' => 'Fund updated successfully',
            'data' => $resource
        ])->setStatusCode(200);
    }

    public function destroy(Fund $fund): JsonResponse
    {
        return response()->json([
            'message' => 'Fund Deleted successfully',
            'data' => $this->service->delete($fund)
        ])->setStatusCode(200);
    }

    public function duplicatedFunds(Request $request): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => FundResource::collection($this->service->duplicatedFunds())->response()->getData()
        ])->setStatusCode(200);
    }
}
