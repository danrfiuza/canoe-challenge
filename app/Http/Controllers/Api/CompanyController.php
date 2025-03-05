<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateCompanyRequest;
use App\Http\Requests\Api\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => CompanyResource::collection($this->service->index($request->all()))->response()->getData()
        ])->setStatusCode(200);
    }

    public function show(Company $company): JsonResponse
    {
        return response()->json([
            'message' => '',
            'data' => CompanyResource::make($company)
        ])->setStatusCode(200);
    }

    public function store(CreateCompanyRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Company created successfully',
            'data' => CompanyResource::make($this->service->create($request->validated()))
        ])->setStatusCode(201);
    }

    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        return response()->json([
            'message' => 'Company updated successfully',
            'data' => CompanyResource::make($this->service->update($company, $request->validated()))
        ])->setStatusCode(200);
    }

    public function destroy(Company $company): JsonResponse
    {
        return response()->json([
            'message' => 'Company Deleted successfully',
            'data' => $this->service->delete($company)
        ])->setStatusCode(200);
    }
}
