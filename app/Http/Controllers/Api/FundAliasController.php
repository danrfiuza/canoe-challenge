<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateFundAliasRequest;
use App\Http\Requests\Api\UpdateFundAliasRequest;
use App\Http\Resources\FundAliasResource;
use App\Models\FundAlias;
use Illuminate\Http\Request;

use App\Services\FundAliasService;
use Illuminate\Http\JsonResponse;

class FundAliasController extends Controller
{
    public function __construct(private FundAliasService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => FundAliasResource::collection($this->service->index($request->all()))->response()->getData()
        ])->setStatusCode(200);
    }

    public function show(FundAlias $fundAlias): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => FundAliasResource::make($fundAlias)
        ])->setStatusCode(200);
    }

    public function store(CreateFundAliasRequest $request): JsonResponse
    {
        $resource = new FundAliasResource($this->service->create($request->validated()));

        return response()->json([
            'message' => 'Fund Alias created successfully',
            'data' => $resource
        ])->setStatusCode(201);
    }

    public function update(UpdateFundAliasRequest $request, FundAlias $fundAlias)
    {
        $resource = new FundAliasResource($this->service->update($fundAlias, $request->validated()));

        return response()->json([
            'message' => 'Fund Alias updated successfully',
            'data' => $resource
        ])->setStatusCode(200);
    }

    public function destroy(FundAlias $fundAlias): JsonResponse
    {
        return response()->json([
            'message' => 'Fund Alias Deleted successfully',
            'data' => $this->service->delete($fundAlias)
        ])->setStatusCode(200);
    }
}
