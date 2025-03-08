<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\FundAliasController;
use App\Http\Controllers\Api\FundCompanyController;
use App\Http\Controllers\Api\FundController;
use App\Http\Controllers\Api\FundManagerController;
use Illuminate\Support\Facades\Route;

Route::prefix('funds')->group(function () {
    Route::get('duplicated', [FundController::class, 'duplicatedFunds']);
    Route::apiResource('/', FundController::class)->parameters(['' => 'fund']);
});

Route::apiResource('fund-aliases', FundAliasController::class);
Route::apiResource('fund-companies', FundCompanyController::class);
Route::apiResource('fund-managers', FundManagerController::class);
Route::apiResource('companies', CompanyController::class);
