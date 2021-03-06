<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 17:05
 */

namespace ct\socket;


use ct\protocol\IBase;
use Swoole\Client;

abstract class Tcp
{
    /**
     * @var Client
     */
    protected $client;

    protected $callbackQueue;

    protected $host;
    protected $port;

    protected $close = false;



    /**
     * Tcp constructor.
     * @param $host
     * @param $port
     */
    public function __construct($host, $port)
    {
        $this->callbackQueue = new \SplQueue();
        $this->host = $host;
        $this->port = $port;
        $this->client = new Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

        $this->client->on('receive', [$this, 'onReceive']);

        $this->client->on('connect', [$this, 'onConnect']);

        $this->client->on('error', function(Client $cli){
            echo "error connect=>" . $this->host . ":" . $this->port . PHP_EOL;
            $cli->close();
            echo "re connect=>" . $this->host . ":" . $this->port . PHP_EOL;
            $this->connect();
        });

        $this->client->on('close', [$this, 'onClose']);

        $this->init();
    }



    public function onClose(Client $cli)
    {
        echo "close=>" . $this->host . ":" . $this->port . PHP_EOL;
        if($this->close)
            return ;
        echo "re connect=>" . $this->host . ":" . $this->port . PHP_EOL;
        $this->connect();
    }


    public function onConnect(Client $cli)
    {
        echo "connect=>" . $this->host . ":" . $this->port . PHP_EOL;
    }

    public function onReceive(Client $cli, $data)
    {
        $callback = $this->callbackQueue->dequeue();
        call_user_func($callback, $this->decode($data), $cli);
    }



    public function connect()
    {
        $this->client->connect($this->host, $this->port);
    }

    public function close()
    {
        $this->close = true;
        $this->client->close();
    }

    public function send(IBase $proto, $callback)
    {
        $this->client->send($proto->encode());
        $this->callbackQueue->enqueue($callback);

    }

    abstract function init();

    /**
     * @param $data
     * @return IBase
     */
    abstract protected function decode($data);
}