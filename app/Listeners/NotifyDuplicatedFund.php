<?php

namespace App\Listeners;

use App\Events\DuplicatedFundWarning;
use App\Services\FundService;
use Illuminate\Support\Facades\Log;

class NotifyDuplicatedFund
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DuplicatedFundWarning $event): void
    {
        Log::warning(FundService::duplicatedFundWarningMessage($event->fund));
    }
}