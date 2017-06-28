<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 14:50
 */

namespace ct\socket;


class CoRedis extends CoTcp
{

    protected $protoQueue;

    public function __construct($host, $port)
    {
        $tcp = new Redis($host, $port);
        $this->protoQueue = new \SplQueue();
        parent::__construct($tcp);
    }

    public function sendMsg($data,  $context)
    {
        $proto = \ct\protocol\Redis::create($data);
        $this->protoQueue->enqueue($proto);

        $this->send($proto,  $context);
    }

    public function get($key, $context)
    {
        $data = ['get', $key];
        $this->sendMsg($data, $context);
    }

    public function set($key, $value, $context)
    {
        $data = ['set', $key, $value];
        $this->sendMsg($data, $context);
    }

    protected function decode($data)
    {
        $proto = $this->protoQueue->dequeue();
        return \ct\protocol\Redis::decode($data, $proto);
    }


}