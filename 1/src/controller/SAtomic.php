<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 15:44
 */

namespace ct\controller;


use ct\protocol\Simple;
use Swoole\Client;
use Swoole\Http\Request;
use Swoole\Http\Response;

class SAtomic
{
    public function getAction(Request $request, Response $response)
    {
        $client = new Client(SWOOLE_SOCK_TCP);
        $client->connect("127.0.0.1", 9501);
        $client->send("get,\r\n");
        $data = $client->recv();
        $proto = Simple::decode($data);
        $num = $proto->args[0];
        $client->close();
        $response->end("<h1>$num</h1>");
    }
}