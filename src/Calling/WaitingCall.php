<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/15
 * Time: 15:16
 */

namespace Utopia\Co\Calling;


use Utopia\Co\Coroutines\CoroutineTaskInterface;
use Utopia\Co\Scheduler;

/**
 * 等待返回，协程同步代码
 * @package Utopia\Co\Calling
 */
class WaitingCall extends SchedulerCall
{
    /** @var \Generator */
    protected $callback;

    public function __construct($gen)
    {
        $this->callback = $gen;
    }

    public function __invoke(CoroutineTaskInterface $task, Scheduler $scheduler)
    {
        $pid = $scheduler->newCoroutine($this->callback);
        $scheduler->waiting($task->getCoroutineId(),$pid);
    }
}