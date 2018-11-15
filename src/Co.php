<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/6
 * Time: 22:23
 */

namespace Utopia\Co;


class Co
{
    /** @var Scheduler */
    private static $scheduler;
    private static $maxCid = 0;


    /**
     * 生成一个唯一协程id
     * 系统内全局唯一
     */
    public static function newCoroutineId():int
    {
        return ++self::$maxCid;
    }

    /**
     * 把闭包放到协程栈执行
     * @param callable $callable
     * @return int
     */
    public static function go(callable $callable):int
    {
        return self::$scheduler->newCoroutine($callable());
    }

    /**
     * 把值发送到父级协程
     * @param $value
     */
    public static function sendToParentValue($value)
    {

    }

    /**
     * 供外部调用
     * @return Scheduler
     */
    public static function getScheduler():Scheduler
    {
        return self::$scheduler;
    }


    public static function setScheduler(Scheduler $scheduler)
    {
        self::$scheduler = $scheduler;
    }
}