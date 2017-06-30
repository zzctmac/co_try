<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-30
 * Time: 17:03
 */

namespace ct\protocol\redis;


use ct\protocol\Redis;

class Multi implements MethodParser
{

    /**
     * @param $data
     * @param Redis $requestProto
     * @return mixed
     */
    public function decode($data, $requestProto)
    {
        $originStr = $data;
        if(strpos($data, "\r\n") === false)
            return false;
        $data = explode("\r\n", $data);
        $count = substr($data[0], 1) + 0;
        $pos = strlen($data[0]) + 2;
        array_shift($data);
        if(count($data) < $count * 2) {
            return false;
        }

        $res = [];
        $value = $key = null;

        $toggle = 1;

        for($step = 0; $step < $count * 2; $step++) {
            if($step % 2 == 0) {

            } else {
                if($toggle) {
                    $key = $data[$step];
                } else {
                    $value = $data[$step];
                    $res[$key] = $value;
                }
                $toggle = $toggle ^ 1;
            }
            $pos += strlen($data[$step]) + 2;
        }



        if(strlen($originStr) > $pos) {
            $lastStr = substr($originStr, $pos);
        } else {
            $lastStr = '';
        }

        return [$res, $lastStr];


    }
}