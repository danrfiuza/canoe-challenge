<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateFundCompanyRequest;
use App\Http\Requests\Api\UpdateFundCompanyRequest;
use App\Http\Resources\FundCompanyResource;
use App\Models\FundCompany;
    use Illuminate\Http\Request;

use App\Services\FundCompanyService;
use Illuminate\Http\JsonResponse;

class FundCompanyController extends Controller
{
    public function __construct(private FundCompanyService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => FundCompanyResource::collection($this->service->index($request->all()))->response()->getData()
        ])->setStatusCode(200);
    }

    public function store(CreateFundCompanyRequest $request): JsonResponse
    {
        $resource = new FundCompanyResource($this->service->create($request->validated()));

        return response()->json([
            'message' => 'Fund Alias created successfully',
            'data' => $resource
        ])->setStatusCode(201);
    }

    public function destroy(FundCompany $FundCompany): JsonResponse
    {
        return response()->json([
            'message' => 'Fund Alias Deleted successfully',
            'data' => $this->service->delete($FundCompany)
        ])->setStatusCode(200);
    }
}
