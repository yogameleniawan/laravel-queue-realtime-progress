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
        // this is where the magic happen. the data will be processed by your own business logic.
        $this->repository->save(data: $this->data);

        // after the data is processed, we can send the event to the client with pusher broadcast.
        event(new StatusJobEvent(
            finished: $this->batch()->finished(),
            progress: $this->batch()->progress(),
            pending: $this->batch()->processedJobs(),
            total: $this->batch()->totalJobs,
            data: $this->data
        ));
    }
}
