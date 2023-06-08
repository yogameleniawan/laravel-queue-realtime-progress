<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs\MasterJob;

class RealtimeJobBatch {

    public function __construct(
        private RealtimeJobBatchInterface $realtimeJob,

    )
    {
        //
    }

    public function execute(string $name, ?array $option): object {
        $batch = Bus::batch(
            new MasterJob(
                option: $option,
                repository: $this->realtimeJob
            )
        )
        ->name("Master Job of {$name}") // it will show in your job batch name as master job
        ->dispatch();

        return $batch;
    }
}
