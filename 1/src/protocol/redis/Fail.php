<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-30
 * Time: 18:08
 */

namespace ct\protocol\redis;



class Fail extends Success
{
    public function decode($data, $requestProto)
    {
        $res =  parent::decode($data, $requestProto);
        if($res === false)
            return false;
        $res[0] = false;
        return $res;
    }


}