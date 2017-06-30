<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 15:46
 */

namespace ct\protocol\redis;


use ct\protocol\Redis;

class Success implements MethodParser
{

    /**
     * @param $data
     * @param Redis $requestProto
     * @return mixed
     */
    public function decode($data, $requestProto)
    {
        $pos = strpos($data, "\r\n");
        if($pos === false)
            return false;
        $lastSecondPos = strlen($data) - 2;

        if($pos != $lastSecondPos) {
            $lastStr = substr($data, $pos + 2);
        } else {
            $lastStr = '';
        }
        if($requestProto->isPing) {
            $res = substr($data, 1, strlen($data) - 3);
            $msg = null;
        } else {
            $res = true;
            $msg = substr($data, 1, strlen($data) - 3);
        }
        return [$res, $lastStr, $msg];
    }
}