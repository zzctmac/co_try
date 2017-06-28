<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 15:22
 */

namespace ct\protocol\redis;


use ct\protocol\Redis;

class Get implements MethodParser
{

    /**
     * @param $data
     * @param Redis $requestProto
     * @return mixed
     */
    public function decode($data, $requestProto)
    {
        $data = array_diff(explode("\r\n", $data), [""]);
        $lengthStr = $data[0];
        if(strpos($lengthStr, "$") !== 0) {
            var_dump($data);
            return null;
        } else {
            $length = substr($lengthStr, 1) + 0;
            if($length == -1) {
                return null;
            } else {
                return $data[1];
            }
        }
    }
}