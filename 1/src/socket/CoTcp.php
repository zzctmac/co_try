<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 14:50
 */

namespace ct\socket;


use ct\protocol\IBase;
use Swoole\Client;

abstract class CoTcp extends Tcp
{

    protected $tcp;

    protected $coQueue;

    protected $readyCallQueue;

    public static $coQueueMaxNum = 30;

    public function __construct(Tcp $tcp)
    {
        $this->tcp = $tcp;
        $this->tcp->client->on('receive', [$this, 'onReceive']);
        $this->coQueue = new \SplQueue();
        $this->readyCallQueue = new \SplQueue();
    }

    public function connect()
    {
        $this->tcp->connect();
    }


    function init()
    {

    }

    public function onReceive(Client $cli, $data)
    {
        if($this->readyCallQueue->count() > 0) {
            list($proto, $co) = $this->readyCallQueue->dequeue();
            $this->send($proto, $co);
        }
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
        if($this->coQueue->count() >= static::$coQueueMaxNum) {
            echo 'wait too much';
            $this->readyCallQueue->enqueue([$proto, $co]);
        }  else {
            $this->tcp->client->send($proto->encode());
            $this->coQueue->enqueue($co);
        }

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