<?php
/**
 * Created by PhpStorm.
 * User: 明月有色
 * Date: 2018/11/15
 * Time: 11:18
 */

use Utopia\Co\Client\Mysql\Db;

require '../vendor/autoload.php';


$dbConfig = new \Utopia\Co\Client\Mysql\Config();
$dbConfig->set(
    [
        "count"    => '2',// 连接池数量
        "host"     => '192.168.100.99',
        "user"     => 'root',
        "password" => '123456',
        "database" => 'test',
    ]
);
Db::addConnect($dbConfig);


$scheduler = new \Utopia\Co\Scheduler();

go(function (){
    $data[] = yield Db::query('select sleep(2)');
    var_dump($data);
});

go(
    function () {

        go(
            function () {
                yield;
                echo "ok\n";
            }
        );

        $data[] = yield Db::query('select * from role limit 0,1');

        var_dump($data);
    }
);
go(function (){
    $data[] = yield Db::query('select sleep(2)');
    var_dump($data);
});
/**
 * 模拟socket监听
 */
while (1) {
    $hasCo   = $scheduler->run();
    $timeout = $hasCo ? 0 : null;
    /**
     * 根据是否还有协程，设置超时时间
     * stream_select($rSocks, $wSocks, $eSocks, $timeout)
     */
}
