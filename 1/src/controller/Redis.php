<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 14:29
 */

namespace ct\controller;


use Swoole\Http\Request;
use Swoole\Http\Response;

class Redis
{
    public function simpleAction(Request $request, Response $response)
    {
        $key = 'simple';

        $redis = new \Redis();
        $redis->connect("127.0.0.1", 6379);

        $res = $redis->get($key);

        $str = "<h1>$res</h1>";


        $redis->set($key, str_shuffle("abcdeefghijklmn"));

        $res = $redis->get($key);

        $str .= "<h1>$res</h1>";

        $response->end($str);

        $redis->close();
    }

    public function shortAction(Request $request, Response $response)
    {
        $redis = new \Redis();
        $redis->connect("127.0.0.1", 6379);
        $redis->set('s', 1);
        $data = $redis->get('s');



        $redis->close();
        unset($redis);

        $response->write("<h1>$data</h1>");
    }
}