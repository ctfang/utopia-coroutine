# utopia-coroutine
原生协程

~~~~php
$scheduler = new \Utopia\Co\Scheduler();

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

