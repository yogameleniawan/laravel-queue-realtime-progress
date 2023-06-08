<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces;

use Illuminate\Support\Collection;

interface RealtimeJobBatchInterface {
    public function get_all(): Collection;
    public function save($data): void;
}
