<?php

namespace App\Task;

use App\Services\OrderService;
use App\Services\WxpayProfitsharingService;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Support\Facades\Log;

class ProfitsharingTask extends Task
{
    private $id;
    private $taskName;
    public function __construct(int $id,string $taskName)
    {
        $this->id = $id;
        $this->taskName = $taskName;
    }

    public function handle()
    {

        try {
            if($this->taskName == 'posting'){
                //提交分账
                (new WxpayProfitsharingService())->postingForTask($this->id);
            }elseif($this->taskName == 'finishing'){
                //完结分账
//                (new WxpayProfitsharingService())->finishForTask($this->id);
            }


        }catch (\Throwable $e) {

            Log::channel('stderr')->error($e->getMessage());
        }
    }

}