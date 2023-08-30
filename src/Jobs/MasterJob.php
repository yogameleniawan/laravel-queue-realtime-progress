<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Events\StatusJobEvent;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\RealtimeJobBatchInterface;

class MasterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $timeout = 10000000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public RealtimeJobBatchInterface $repository
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // we need to create a batch job first. this job will be handle one process at a time.
        $batch = Bus::batch([])
            ->name($this->batch()->id)
            ->finally(function (Batch $batch) {
                event(new StatusJobEvent(
                    finished: true,
                    progress: 0,
                    pending: 0,
                    total: 0,
                    data: ''
                ));
            })
            ->onQueue('default')
            ->dispatch();

        // get all data from database. the data should be in collection.
        $data = $this->repository->get_all();

        // we need to add the batch job to the batch process.
        foreach ($data as $key => $value) {
            $batch->add(
                new BatchJob(
                    data: $value,
                    repository: $this->repository
                ),
            );
        }
    }
}
