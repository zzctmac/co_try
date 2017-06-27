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

    public function __construct(Tcp $tcp)
    {
        $this->tcp = $tcp;
        $this->tcp->client->on('receive', [$this, 'onReceive']);
        $this->coQueue = new \SplQueue();
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
        $co = $this->coQueue->dequeue();
        $co->runCoroutine($this->decode($data));
    }


    public function close()
    {
        $this->tcp->close();
    }

    public function send(IBase $proto, $co)
    {
        $this->tcp->client->send($proto->encode());
        $this->coQueue->enqueue($co);

    }

    /**
     * @param $data
     * @return IBase
     */
    public function decode($data)
    {
        return $this->tcp->decode($data);
    }
}