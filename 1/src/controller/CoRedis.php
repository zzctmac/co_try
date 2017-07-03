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

    public function pingAction(Request $request, Response $response)
    {
        $msg = (yield GlobalObject::getCoRedisClient()->ping($this));
        $response->end($msg);
    }


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

    public function hashAction(Request $request, Response $response)
    {
        $key = 'hk';

        $redis = GlobalObject::getCoRedisClient();

        yield $redis->hSet($key, 'name', 'zzc_' . str_shuffle('abcdefhij'), $this);

        $data = (yield $redis->hGet($key, 'name', $this));



        $response->end($data);

    }

    public function hashallAction(Request $request, Response $response)
    {
        $key = 'hk_' . str_shuffle('abcdef');
        $response->write("<h1>$key</h1>");
        $redis = GlobalObject::getCoRedisClient();

        $arr = ['name'=>'zzc', 'age'=>23];
        $res = (yield $redis->hMSet($key, $arr, $this));
        $response->write($res . "");

        $data = (yield $redis->hGetAll($key, $this));


        $response->write($data['name'] . ' ' . $data['age']);

    }

    public function shortAction(Request $request, Response $response)
    {
        $redis = new \ct\socket\CoRedis("127.0.0.1", 6379);

        yield $redis->coConnect($this);


        yield $redis->set('s', 1, $this);

        $data = (yield $redis->get('s', $this));

        $redis->close();



        $response->write("<h1>$data</h1>");
    }

    public function longAction(Request $request, Response $response)
    {
        $redis = GlobalObject::getCoRedisClient();



        yield $redis->set('s', 1, $this);

        $data = (yield $redis->get('s', $this));




        $response->write("<h1>$data</h1>");
    }

    public function setexAction(Request $request, Response $response)
    {
        $redis = GlobalObject::getCoRedisClient();
        yield $redis->setex('exk', 20, date('Y-m-d H:i:s'), $this);
        $response->write("success");
    }

    public function readexAction(Request $request, Response $response)
    {
        $redis = GlobalObject::getCoRedisClient();
        $v = (yield $redis->get('exk', $this));
        $response->write($v);
    }

    public function mAction(Request $request, Response $response)
    {
        $arr = [];
        $keys = [];
        for($i = 0; $i < 3; $i++)
        {
            $key = 'key_' .  str_shuffle('abcde');
            $value = 'value_' . str_shuffle('ghjkl');
            $response->write("<h1>$key:$value</h1>");
            $arr[$key] = $value;
            $keys[] = $key;
        }

        $redis = GlobalObject::getCoRedisClient();
        yield $redis->mSet($arr, $this);

        $list = (yield $redis->mGet($keys, $this));

        $response->write("<hr />");
        foreach ($list as $key=>$value)
        {
            $response->write("<h1>$key:$value</h1>");
        }

    }
}