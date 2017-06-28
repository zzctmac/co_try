<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 15:00
 */

namespace ct\controller;


use ct\GlobalObject;
use Swoole\Http\Request;
use Swoole\Http\Response;

class CoRedis extends Co
{
    public function simpleAction(Request $request, Response $response)
    {
        $key = 'simple';


        $msg = (yield GlobalObject::getCoRedisClient()->get($key, $this));
        $str  = "<h1>" . $msg . "</h1>";

        yield GlobalObject::getCoRedisClient()->set($key, str_shuffle("abcdeefghijklmn"), $this);

        $msg = (yield GlobalObject::getCoRedisClient()->get($key, $this));
        $str .= "<h1>" . $msg . "</h1>";
        $response->end($str);
    }
}