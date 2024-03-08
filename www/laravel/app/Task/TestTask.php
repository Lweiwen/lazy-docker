<?php

namespace App\Task;

use App\Services\OrderService;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Support\Facades\Log;

class TestTask extends Task
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * 支付任务
     * The logic of handling task
     * @return void
     */
    public function handle()
    {
        Log::channel('stderr')->info(__CLASS__ . ':handle start', [$this->data]);

        try {
            (new OrderService())->completeOrder($this->data);
        }catch (\Throwable $e) {
            Log::channel('stderr')->error($e->getMessage());
        }

    }
}