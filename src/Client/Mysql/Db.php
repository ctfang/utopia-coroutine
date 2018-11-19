<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/15
 * Time: 12:01
 */

namespace Utopia\Co\Client\Mysql;

use Utopia\Co\Calling\WaitingCall;

class Db
{
    /** @var Connect */
    protected static $default;
    protected static $connectMap = [];

    /**
     * @param Config $config
     */
    public static function addConnect(Config $config)
    {
        if( $config->name=='default' ){
            self::$default = new Connect($config);
        }else{
            self::$connectMap[$config->name] = new Connect($config);
        }
    }

    /**
     * @param string $sql
     * @return WaitingCall
     */
    public static function query(string $sql)
    {
        return self::$default->query($sql);
    }
}