<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 15:46
 */

namespace ct\protocol\redis;


use ct\protocol\Redis;

class Set implements MethodParser
{

    /**
     * @param $data
     * @param Redis $requestProto
     * @return mixed
     */
    public function decode($data, $requestProto)
    {
        if(strpos($data, '+') === 0) {
            return true;
        } else {
            return false;
        }
    }
}