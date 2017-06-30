<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 15:22
 */

namespace ct\protocol\redis;


use ct\protocol\Redis;

class Single implements MethodParser
{

    /**
     * @param $data
     * @param Redis $requestProto
     * @return mixed
     */
    public function decode($data, $requestProto)
    {
        $originStr = $data;
        $data = array_diff(explode("\r\n", $data), [""]);
        if(count($data) < 2) {
            return false;
        }
        $packetLength = 4 + strlen($data[0]) + strlen($data[1]);
        $originLength = strlen($originStr);
        if($originLength > $packetLength) {
            $lastStr = substr($originStr, $packetLength);
        } else {
            $lastStr = '';
        }
        return [$data[1], $lastStr];
    }
}