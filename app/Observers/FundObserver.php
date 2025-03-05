<?php

namespace App\Observers;

use App\Events\DuplicatedFundWarning;
use App\Models\Fund;
use App\Services\FundService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

class FundObserver implements ShouldHandleEventsAfterCommit
{
    public function __construct(private readonly FundService $fundService) {}

    public function created(Fund $fund)
    {
        $this->fundService->dispatchDuplicatedFundWarningEvent($fund);
    }

    public function updated(Fund $fund)
    {
        $this->fundService->dispatchDuplicatedFundWarningEvent($fund);
    }
}
