<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/17
 * Time: 21:24
 */

namespace Utopia\Co\Client\Mysql;


class Config
{
    /** @var string 数据唯一名称 */
    public $name = 'default';
    /** @var int 连接池数量 */
    public $count = 1;
    public $host = '127.0.0.1';
    public $user = 'root';
    public $password = '123456';
    public $database = 'utopia';

    public function __construct($name='default')
    {
        $this->name = $name;
    }

    /**
     * @param array $config
     */
    public function set(array $config)
    {
        $this->count    = $config["count"];
        $this->host     = $config["host"];
        $this->user     = $config["user"];
        $this->password = $config["password"];
        $this->database = $config["database"];
    }
}