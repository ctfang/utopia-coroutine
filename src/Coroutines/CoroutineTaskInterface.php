<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/6
 * Time: 23:40
 */

namespace Utopia\Co\Coroutines;


interface CoroutineTaskInterface
{
    /**
     * 获取当前协程id
     * @return int
     */
    public function getCoroutineId();

    /**
     * 获取当前父级协程id
     * @return int
     */
    public function getParentCoroutineId();
    
    /**
     * 设置发送的值
     * @param $sendValue
     * @return mixed
     */
    public function setSendValue($sendValue);

    /**
     * 执行
     * @return mixed
     */
    public function run();

    /**
     * 是否结束
     * @return mixed
     */
    public function isFinished():bool ;
}