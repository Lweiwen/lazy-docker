<?php

namespace App\Task;

use App\Services\OrderService;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Support\Facades\Log;

class TestRefundTask extends Task
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * 退款任务
     * The logic of handling task
     * @return void
     */
    public function handle()
    {
        Log::channel('stderr')->info(__CLASS__ . ':handle start', [$this->data]);

        try {
            (new OrderService())->refundApplyHandle($this->data);
        }catch (\Exception $e) {
            Log::channel('stderr')->error($e->getMessage());
        }
    }

}