<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardStatsUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $clinicId,
        public array $stats
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('clinic.' . $this->clinicId)];
    }

    public function broadcastAs(): string { return 'dashboard.stats.updated'; }

    public function broadcastWith(): array
    {
        return $this->stats;
    }
}