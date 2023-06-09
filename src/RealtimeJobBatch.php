<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs\MasterJob;

class RealtimeJobBatch {
    protected static RealtimeJobBatchInterface $repository;

    /**
     * Set the repository implementation.
     *
     * @return object
     */
    public static function setRepository(
        RealtimeJobBatchInterface $repository
    ): object {
        static::$repository = $repository;

        return new static($repository);
    }

    /**
     * Execute the job batch.
     * $name parameter will be used as the name of the job batch.
     *
     * @param string $name
     * @return object
     */
    public static function execute(string $name): object {
        $batch = Bus::batch(
            new MasterJob(
                repository: static::$repository
            )
        )
        ->name("Master Job of {$name}") // it will show in your job batch name as master job
        ->dispatch();

        return $batch;
    }
}
