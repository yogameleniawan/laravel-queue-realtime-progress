<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Events\StatusJobEvent;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;

class BatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $timeout = 10000000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private $data,
        private RealtimeJobBatchInterface $repository
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // execute single job
        $this->repository->save(data: $this->data);

        // broacast to channel and event
        event(new StatusJobEvent(
            finished: $this->batch()->finished(),
            progress: $this->batch()->progress(),
            pending: $this->batch()->processedJobs(),
            total: $this->batch()->totalJobs,
            data: $this->data
        ));
    }
}
