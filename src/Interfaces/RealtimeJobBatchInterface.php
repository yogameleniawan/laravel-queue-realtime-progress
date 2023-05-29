<?php

namespace YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces;

interface RealtimeJobBatchInterface {
    public function get_all(): object;
    public function save(): void;
}
