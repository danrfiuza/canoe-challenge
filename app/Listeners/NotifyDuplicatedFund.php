<?php

namespace App\Listeners;

use App\Events\DuplicatedFundWarning;
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
        Log::warning("Duplicate Fund Warning: Fund '{$event->fund->name}' with manager '{$event->fund->fundManager->name}' detected.");
    }
}
