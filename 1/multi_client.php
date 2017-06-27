<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-27
 * Time: 16:40
 */

include  __DIR__ . '/vendor/autoload.php';

class SimpleImpl extends \ct\socket\Simple
{
    public function onConnect(\Swoole\Client $cli)
    {
        $this->get(function(\ct\protocol\Simple $proto){
           echo $proto->args[0] . "\n";
   //        $this->close();
        });
    }

}

$s = new SimpleImpl("127.0.0.1", 9501);

$s->connect();

$s1 = new SimpleImpl("127.0.0.1", 9501);

$s1->connect();

$s2 = new SimpleImpl("127.0.0.1", 9501);

$s2->connect();