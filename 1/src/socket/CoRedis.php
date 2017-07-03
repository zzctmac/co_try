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
    protected $requestProto = null;

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

        $msg = $this->decode($data);
        if($msg !== false) {
            $co = $this->coQueue->dequeue();
            list($res, $buf, $_) = $msg;
            $this->buf = $buf;
            if(count($msg) > 2) {
                $this->info = $msg[2];
            }
            else
                $this->info = null;
            $runArr = [[$co, $res]];

            do {
                $data = $this->buf;
                if($data == "")
                    break;
                $msg = $this->decode($data);
                if($msg === false) {
                    $this->protoQueue->enqueue($this->requestProto);
                    break;
                }
                $co = $this->coQueue->dequeue();
                list($res, $buf, $_) = $msg;
                $this->buf = $buf;
                $runArr[] = [$co, $res];
            }while(true);
            foreach ($runArr as $runItem) {
                $runItem[0]->runCoroutine($res);
            }
        } else {
            $this->protoQueue->enqueue($this->requestProto);
        }
    }

    protected function decode($data)
    {
        $proto = $this->protoQueue->dequeue();
        $this->requestProto = $proto;
        return \ct\protocol\Redis::decode($data, $proto);
    }

    protected static function mergeToDataByArr($data, $arr)
    {
        foreach ($arr as $k=>$v) {
            $data[] = $k;
            $data[] = $v;
        }
        return $data;
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

    public function setex($key, $ttl, $value, $context)
    {
        $data = ['setex', $key, $ttl, $value];
        $this->sendMsg($data, $context);
    }

    public function setnx($key, $value, $context)
    {
        $data = ['setnx', $key, $value];
        $this->sendMsg($data, $context);
    }

    public function mSet( $arr, $context)
    {
        $data = self::mergeToDataByArr(['mset'], $arr);
        $this->sendMsg($data, $context);
    }

    public function mGet($arr, $context)
    {
        $data = ['mget'];
        $data = array_merge($data, $arr);
        $this->sendMsg($data, $context);
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
        $data = self::mergeToDataByArr($data, $arr);
        $this->sendMsg($data, $context);
    }

    public function hMGet($key, $arr, $context)
    {
        $data = array_merge(['hmget', $key], $arr);
        $this->sendMsg($data, $context);
    }





}