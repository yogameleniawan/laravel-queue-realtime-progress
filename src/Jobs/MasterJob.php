<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
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
        private $option,
        public RealtimeJobBatchInterface $repository
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
        $batch = Bus::batch([])
            ->name($this->batch()->id)
            ->onQueue('default')
            ->dispatch();

        $data = $this->repository->get_all();

        foreach($data as $key => $value) {
            $batch->add(new BatchJob(
                data: $value,
                repository: $this->repository
                ),
            );
        }
    }
}
