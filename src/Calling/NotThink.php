<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/2
 * Time: 15:14
 */

namespace Utopia\Co\Calling;


use Utopia\Co\Coroutines\CoroutineTaskInterface;
use Utopia\Co\Scheduler;

class NotThink extends SchedulerCall
{
    public function __invoke(CoroutineTaskInterface $task, Scheduler $scheduler)
    {

    }
}