<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 14:38
 */

namespace ct\socket;


use ct\protocol\IBase;

class Redis extends Tcp
{



    function init()
    {
        // TODO: Implement init() method.
    }

    /**
     * @param $data
     * @return IBase
     */
    public function decode($data)
    {
        return \ct\protocol\Redis::decode($data);
    }
}