<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/10
 * Time: 16:08
 */

namespace Utopia\Co;


use Utopia\Co\Calling\SchedulerCall;
use Utopia\Co\Coroutines\CoroutineTask;
use Utopia\Co\Coroutines\CoroutineTaskInterface;

class Scheduler
{
    /** @var array 正在执行的协程 */
    protected $runningCoroutine = [];
    /** @var array 进入等待的协程 */
    protected $waitingCoroutine = [];
    /** @var array 等待协程发起者，发起者结束时或主动恢复时，等待协程才可以恢复 */
    protected $callWaitingCoroutine = [];
    /** @var \SplQueue 所有协程 */
    protected $coroutineQueue;

    /** @var int */
    protected static $cid = 0;
    /** @var int */
    protected static $pcid = 0;

    public function __construct()
    {
        $this->coroutineQueue = new \SplQueue();
        Co::setScheduler($this);
    }

    /**
     * 获取正在执行协程
     * @return int
     */
    public static function getRunningCid()
    {
        return self::$cid;
    }

    /**
     * 获取正在执行父级协程
     * @return int
     */
    public static function getRunningPCid()
    {
        return self::$pcid;
    }

    /**
     * 创建一个协程
     * @param \Generator $coroutine
     * @return int
     */
    public function newCoroutine(\Generator $coroutine)
    {
        $cid                          = Co::newCoroutineId();
        $pcid                         = self::$cid;
        $task                         = new CoroutineTask($cid, $pcid, $coroutine);
        $this->runningCoroutine[$cid] = $task;
        $this->schedule($task);

        return $cid;
    }

    /**
     * 放到执行栈
     * @param CoroutineTaskInterface $task
     */
    public function schedule(CoroutineTaskInterface $task)
    {
        $this->coroutineQueue->enqueue($task);
    }

    /**
     * 是否还有正在运行的协程栈
     * @return bool
     */
    public function hasRunning(): bool
    {
        return !$this->coroutineQueue->isEmpty();
    }

    /**
     * @return bool true 没有协程列表 false 还有
     */
    public function run():bool
    {
        /** 协程执行 */
        if (!$this->coroutineQueue->isEmpty()) {
            /** @var CoroutineTaskInterface $task */
            $task = $this->coroutineQueue->dequeue();

            self::$cid  = $task->getCoroutineId();
            self::$pcid = $task->getParentCoroutineId();

            $return = $task->run();

            if ($return instanceof SchedulerCall) {
                $return($task, $this);
                return false;
            }

            if ($task->isFinished()) {
                $cid = $task->getCoroutineId();
                unset($this->runningCoroutine[$cid]);
                if (isset($this->callWaitingCoroutine[$cid])) {
                    foreach ($this->callWaitingCoroutine[$cid] as $waitingPCid) {
                        $this->recover($waitingPCid, $cid);
                    }
                    unset($this->callWaitingCoroutine[$cid]);
                }
            } else {
                $this->schedule($task);
            }
            return false;
        }
        return true;
    }

    /**
     * 父级进入等待列表
     * @param int $pcid 被等待
     * @param int $callCid 发起者
     */
    public function waiting(int $pcid, int $callCid)
    {
        if (isset($this->runningCoroutine[$pcid])) {
            $waitingCoroutine                            = $this->runningCoroutine[$pcid];
            $this->waitingCoroutine[$pcid]               = $waitingCoroutine;
            $this->callWaitingCoroutine[$callCid][$pcid] = time();

            unset($this->runningCoroutine[$pcid]);
        }
    }

    /**
     * 复原协程
     * @param int $pcid 被等待
     * @param int $callCid 发起者
     */
    public function recover(int $pcid, int $callCid)
    {
        if (isset($this->waitingCoroutine[$pcid])) {
            $waitingCoroutine                            = $this->waitingCoroutine[$pcid];
            $this->runningCoroutine[$pcid]               = $waitingCoroutine;
            $this->callWaitingCoroutine[$callCid][$pcid] = time();

            $this->schedule($waitingCoroutine);

            unset($this->waitingCoroutine[$pcid]);
            unset($this->callWaitingCoroutine[$callCid][$pcid]);
        }
    }
}