<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs\MasterJob;

class RealtimeJobBatch {
    public static RealtimeJobBatchInterface $realtimeJob;

    public function __construct(
        private RealtimeJobBatchInterface $interface,
    )
    {
        self::$realtimeJob = $this->interface;
    }

    public static function execute(string $name): object {
        $batch = Bus::batch(
            new MasterJob(
                repository: self::$realtimeJob
            )
        )
        ->name("Master Job of {$name}") // it will show in your job batch name as master job
        ->dispatch();

        return $batch;
    }
}
