<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 14:50
 */

namespace ct\socket;


use ct\co\ReturnValue;
use ct\protocol\IBase;
use Swoole\Client;

abstract class CoTcp extends Tcp
{

    protected $tcp;

    protected $coQueue;


    public static $coQueueMaxNum = 30;

    protected $isCoConnect = false;
    protected $isCoClose = false;

    public function __construct(Tcp $tcp)
    {
        $this->tcp = $tcp;
        $this->tcp->client->on('receive', [$this, 'onReceive']);
        $this->coQueue = new \SplQueue();
        $this->tcp->client->on('connect', [$this, 'onConnect']);
        $this->tcp->client->on('close', [$this, 'onClose']);
    }

    public function connect()
    {
        $this->tcp->connect();
    }

    public function coConnect($context)
    {
        $this->isCoConnect = true;
        $this->coQueue->enqueue($context);
        $this->connect();
    }

    public function onConnect(Client $cli)
    {
        $this->tcp->onConnect($cli);
        if($this->isCoConnect) {
            $co = $this->coQueue->dequeue();
            $co->runCoroutine('connect');
        }
    }



    public function onClose(Client $cli)
    {
        if($this->isCoClose) {
            $co = $this->coQueue->dequeue();
            $co->runCoroutine('close');
        }
        $this->tcp->onClose($cli);
    }


    function init()
    {

    }

    public function onReceive(Client $cli, $data)
    {
        $co = $this->coQueue->dequeue();
        $msg = $this->decode($data);
        $co->runCoroutine($msg);


    }


    public function close()
    {
        $this->tcp->close();
    }

    public function send(IBase $proto, $co)
    {
        $msg = $proto->encode();
            $this->tcp->client->send($msg);
            $this->coQueue->enqueue($co);
    }

    /**
     * @param $data
     * @return IBase
     */
    protected function decode($data)
    {
        return $this->tcp->decode($data);
    }


}