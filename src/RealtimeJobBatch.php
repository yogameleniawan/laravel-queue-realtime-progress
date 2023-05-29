<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs\MasterJob;

class RealtimeJobBatch {

    public function execute(string $name, ?array $option, string $channel_name, string $broadcast_name): object {
        $batch = Bus::batch(
            new MasterJob(
                option: $option,
                channel_name: $channel_name,
                broadcast_name: $broadcast_name
            )
        )
        ->name("Master Job of {$name}") // it will show in your job batch name as master job
        ->dispatch();

        return $batch;
    }
}
