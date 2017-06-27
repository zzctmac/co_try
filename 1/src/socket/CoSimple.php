<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 15:05
 */

namespace ct\socket;


use ct\co\Context;

class CoSimple extends CoTcp
{
    public function __construct($host, $port)
    {
        $tcp = new Simple($host, $port);
        parent::__construct($tcp);
    }

    public function sendMsg($method, $args,  $context)
    {
        $this->send(\ct\protocol\Simple::create($method, $args),  $context);
    }

    public function add($num = 1,  $context)
    {
        $this->sendMsg('add', [$num],  $context);
    }

    public function sub($num = 1,  $context)
    {
        $this->sendMsg('sub', [$num],  $context);
    }

    public function get( $context)
    {
        $this->sendMsg('get', [],  $context);
    }
}