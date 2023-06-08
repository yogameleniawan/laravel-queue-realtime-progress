<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces;

use Illuminate\Support\Collection;

interface RealtimeJobBatchInterface {

    /**
     * Get all data from database. The data should be in collection.
     *
     * @return \Illuminate\Support\Collection;
     */
    public function get_all(): Collection;

    /**
     * This method can be used to save data to database.
     * In this case, the data can process by your own business logic.
     *
     * @return void
     */
    public function save($data): void;
}
