<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/15
 * Time: 15:40
 */

namespace Utopia\Co\Calling;


use Utopia\Co\Coroutines\CoroutineTaskInterface;
use Utopia\Co\Scheduler;

/**
 * Class SendValueCall
 * 设置发送给协程的值
 * @package Utopia\Co\Calling
 */
class SendValueCall extends SchedulerCall
{
    private $value;
    private $sendCid;

    public function __construct($value, $sendCid)
    {
        $this->value   = $value;
        $this->sendCid = $sendCid;
    }

    public function __invoke(CoroutineTaskInterface $task, Scheduler $scheduler)
    {
        $scheduler->sendValue($this->sendCid, $this->value);
    }
}