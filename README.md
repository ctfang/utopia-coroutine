# utopia-coroutine
原生协程

~~~~php
$scheduler = new \Utopia\Co\Scheduler();
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


go(function (){
    $data[] = yield Db::query('select sleep(2)');
    var_dump($data);
});
go(function (){
    echo "协程 1 开始\n";
    for ($i=0;$i<10000;$i++){
        yield co_sleep(1);
        echo "协程 1 ：{$i}\n";
    }
});

go(function (){
    echo "协程 2 开始\n";
    yield co_sleep(1);
    echo "协程 2：{2222222222222222}\n";
    yield co_sleep(1);
    echo "协程 2：{3333333333333333}\n";
    echo "协程 2 结束\n";
});


/**
 * 模拟socket监听
 */
while (1){
    $hasCo = $scheduler->run();
    $timeout = $hasCo?0:null;
    /**
     * 根据是否还有协程，设置超时时间
     * stream_select($rSocks, $wSocks, $eSocks, $timeout)
     */
}
~~~~

协程异步执sql
~~~~php

~~~~