<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 16:39
 */
include  __DIR__ . '/vendor/autoload.php';
\ct\storage\Handler::$atomic = new \Swoole\Atomic(0);

$serv = new Swoole\Server("127.0.0.1", 9501);
$serv->set(array(
    'worker_num' => 2,   //工作进程数量
    'backlog' => 128,   //listen backlog
    'max_request' => 50,
    'dispatch_mode' => 2,
    'open_eof_split' => true, //打开EOF_SPLIT检测
    'package_eof' => "\r\n", //设置EOF
));
$serv->on('connect', function ($serv, $fd){
    echo "Client:Connect.\n";
});
$serv->on('receive', function ($serv, $fd, $from_id, $data) {

    try {
        $simple = \ct\protocol\Simple::decode($data);
        $string = \ct\storage\Handler::handle($simple)->encode();
    }catch (\ct\protocol\ProtocolException $e) {
        $string = \ct\protocol\Simple::create('error', [$e->getMessage()])->encode();
    }


    $serv->send($fd, $string);

});
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});
$serv->start();