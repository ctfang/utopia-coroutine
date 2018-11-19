<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/15
 * Time: 16:24
 */

namespace Utopia\Co\Calling;


use Utopia\Co\Coroutines\CoroutineTaskInterface;
use Utopia\Co\Scheduler;

/**
 * 设置最后的值
 * @package Utopia\Co\Calling
 */
class ReturnValueCall extends SchedulerCall
{
    private $value;

    public function __construct($value)
    {
        $this->value   = $value;
    }

    public function __invoke(CoroutineTaskInterface $task, Scheduler $scheduler)
    {
        $pcid = $task->getParentCoroutineId();
        //$scheduler->recover($pcid,$task->getCoroutineId());
        $scheduler->sendValue($pcid,$this->value);
        $scheduler->schedule($task);
    }
}