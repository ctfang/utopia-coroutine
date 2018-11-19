<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/18
 * Time: 11:06
 */

namespace Utopia\Co\Client\Mysql;


use Utopia\Co\Calling\ReturnValueCall;
use Utopia\Co\Calling\WaitingCall;

class Connect
{
    private $dbQueue = [];

    public function __construct(Config $config)
    {
        $this->dbQueue = new \SplQueue();

        for($i = $config->count;$i>0;$i--){
            $db = new \mysqli($config->host, $config->user, $config->password, $config->database);
            $this->enqueue($db);
        }
    }

    /**
     * 从连接池获取空闲连接
     * @return \mysqli|null
     */
    protected function getConn()
    {
        if (!$this->dbQueue->isEmpty()) {
            return $this->dbQueue->dequeue();
        }
        return null;
    }

    /**
     * 空闲连接存入池
     * @param $db
     */
    protected function enqueue($db)
    {
        $this->dbQueue->enqueue($db);
    }

    /**
     * @param string $sql
     * @return WaitingCall
     */
    public function query(string $sql)
    {
        return new WaitingCall(self::queryToCo($sql));
    }

    /**
     * 转化为异步协程
     * @param string $sql
     * @return \Generator
     */
    protected function queryToCo(string $sql)
    {
        yield;
        while (!$db = $this->getConn()){
            yield;
        }

        $db->query($sql, MYSQLI_ASYNC);
        yield;
        $links = $errors = $reject = [$db];
        while (!mysqli_poll($links, $errors, $reject, 0)){
            $links = $errors = $reject = [$db];
            yield;
        }
        $data  = [];
        if ($result = $db->reap_async_query()) {
            $data = $result->fetch_array(MYSQLI_ASSOC);
            if (is_object($result)){
                mysqli_free_result($result);
            }
        } else{
            die(sprintf("MySQLi Error: %s", mysqli_error($db)));
        }
        $this->enqueue($db);
        yield new ReturnValueCall($data);
    }
}