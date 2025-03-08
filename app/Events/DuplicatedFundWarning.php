<?php

namespace App\Events;

use App\Models\Fund;
use App\Services\FundService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DuplicatedFundWarning implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public readonly Fund $fund) {}

    public function broadcastOn()
    {
        return [
            new Channel('fund-duplicity'),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => FundService::duplicatedFundWarningMessage($this->fund)
        ];
    }
}
