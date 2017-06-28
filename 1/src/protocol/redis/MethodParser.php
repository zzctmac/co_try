<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 15:18
 */

namespace ct\protocol\redis;


use ct\protocol\Redis;

interface MethodParser
{
    /**
     * @param $data
     * @param Redis $requestProto
     * @return mixed
     */
    public function decode($data, $requestProto);
}