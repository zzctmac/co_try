<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 15:01
 */

namespace ct\controller;


use ct\GlobalObject;
use Swoole\Http\Request;
use Swoole\Http\Response;

class CoAtomic extends Co
{
    public function getAction(Request $request, Response $response)
    {
        $proto = (yield GlobalObject::getCoSimpleClient()->get($this));
        $response->end("<h1>" . $proto->args[0] . "</h1>");
    }

    public function tAction(Request $request, Response $response)
    {
        $proto = (yield GlobalObject::getCoSimpleClient()->get($this));
        $num1 = $proto->args[0];

        yield GlobalObject::getCoSimpleClient()->add(1, $this);
        $proto = (yield GlobalObject::getCoSimpleClient()->get($this));
        $num2 = $proto->args[0];

        yield GlobalObject::getCoSimpleClient()->sub(1, $this);
        $proto = (yield GlobalObject::getCoSimpleClient()->get($this));
        $num3 = $proto->args[0];

        $str = sprintf("<h1>%d:%d:%d</h1>", $num1, $num2, $num3);
        $response->end($str);
    }
}