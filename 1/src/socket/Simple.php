<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 17:34
 */

namespace ct\socket;


use ct\protocol\IBase;

class Simple extends Tcp
{

    function init()
    {
        $this->client->set(array(
        'open_eof_split' => true, //打开EOF_SPLIT检测
        'package_eof' => "\r\n", //设置EOF
        ));
    }

    /**
     * @param $data
     * @return IBase
     */
    public function decode($data)
    {
        return \ct\protocol\Simple::decode($data);
    }

    public function sendMsg($method, $args, $callback)
    {
        $this->send(\ct\protocol\Simple::create($method, $args), $callback);
    }

    public function add($num = 1, $callback)
    {
        $this->sendMsg('add', [$num], $callback);
    }

    public function sub($num = 1, $callback)
    {
        $this->sendMsg('sub', [$num], $callback);
    }

    public function get($callback)
    {
        $this->sendMsg('get', [], $callback);
    }




}