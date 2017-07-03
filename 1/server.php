<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 16:34
 */

include  __DIR__ . '/vendor/autoload.php';


date_default_timezone_set("Asia/Shanghai");


$serv = new Swoole\Http\Server("0.0.0.0", 9081);

/*$serv->set([
    'worker_num' => 2,
    'max_connection' => 2048

]);*/

$serv->on('Request', function(\Swoole\Http\Request $request,\Swoole\Http\Response $response) {
    try {
        list($c, $a) = \ct\GlobalObject::$route->getController($request);
        if($c instanceof \ct\controller\Co) {
            $c->setCo($c->$a($request, $response));
            $c->runCoroutine();
        } else {
            $c->$a($request, $response);
        }
    }catch (Exception $e) {
        $response->status(502);
        $response->end("<h1>server wrong</h1>");
    }
});

$serv->on('WorkerStart', function ($serv, $worker_id){
    \ct\GlobalObject::$simple = new \ct\socket\Simple("127.0.0.1", 9501);
    \ct\GlobalObject::$simple->connect();

    \ct\GlobalObject::$coSimple = new \ct\socket\CoSimple("127.0.0.1", 9501);
    \ct\GlobalObject::$coSimple->connect();

    \ct\GlobalObject::$coRedis = new \ct\socket\CoRedis("127.0.0.1", 6379);
    \ct\GlobalObject::$coRedis->connect();

    \ct\GlobalObject::$route = new \ct\route\TwoLevel();
});

$serv->start();