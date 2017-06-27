<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 14:37
 */

namespace ct\controller;


use ct\GlobalObject;
use ct\protocol\Simple;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Atomic
{
    public function getAction(Request $request, Response $response)
    {
        GlobalObject::getSimpleClient()->get(function(Simple $proto) use($response) {
            $response->end("<h1>" . $proto->args[0] . "</h1>");
        });
    }

    public function addAction(Request $request, Response $response)
    {
        GlobalObject::getSimpleClient()->add(1, function(Simple $proto) use($response) {
           $response->end($proto->method);
        });
    }

    public function subAction(Request $request, Response $response)
    {
        GlobalObject::getSimpleClient()->sub(1, function(Simple $proto) use($response) {
            $response->end($proto->method);
        });
    }
}