<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 14:50
 */

namespace ct\socket;


use ct\protocol\IBase;
use Swoole\Client;

class CoRedis extends CoTcp
{

    protected $protoQueue;

    protected $buf = '';

    public $info;

    public function __construct($host, $port)
    {
        $tcp = new Redis($host, $port);
        $this->protoQueue = new \SplQueue();
        parent::__construct($tcp);
    }

    public function sendMsg($data,  $context)
    {
        $proto = \ct\protocol\Redis::create($data);
        $this->send($proto,  $context);
    }

    public function send(IBase $proto, $co)
    {
        $this->protoQueue->enqueue($proto);
        parent::send($proto, $co);
    }

    public function onReceive(Client $cli, $data)
    {
        $co = $this->coQueue->dequeue();
        $msg = $this->decode($data);
        if($msg === false) {
            $this->coQueue->enqueue($co);
        } else {
            list($res, $buf) = $msg;
            $this->buf = $buf;
            if(count($msg) > 2) {
                $this->info = $msg[2];
            }
            else
                $this->info = null;
            $co->runCoroutine($res);

            do {
                $data = $this->buf;
                if($data == "")
                    break;
                $msg = $this->decode($data);
                if($msg === false) {
                    break;
                }
                $co = $this->coQueue->dequeue();
                list($res, $buf) = $msg;
                $this->buf = $buf;
                $co->runCoroutine($res);

            }while(true);
        }
    }


    public  function ping($context)
    {
        $proto = \ct\protocol\Redis::create(null);
        $proto->isPing = true;
        $this->send($proto, $context);
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
        return \ct\protocol\Redis::decode($data, $proto, $this->buf);
    }

    public function hSet($key, $k, $v, $context)
    {
        $data = ['hset', $key, $k, $v];
        $this->sendMsg($data, $context);
    }

    public function hGet($key, $k,  $context)
    {
        $data = ['hget', $key, $k];
        $this->sendMsg($data, $context);
    }

    public function hGetAll($key, $context)
    {
        $data = ['hgetall', $key];
        $this->sendMsg($data, $context);
    }

    public function hMSet($key, $arr, $context)
    {
        $data = ['hmset', $key];
        foreach ($arr as $k=>$v) {
            $data[] = $k;
            $data[] = $v;
        }
        $this->sendMsg($data, $context);
    }




}