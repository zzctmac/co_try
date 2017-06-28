<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-28
 * Time: 15:22
 */

namespace ct\protocol\redis;


use ct\protocol\Redis;

class MethodParserFactory
{
    protected static $map = [
        'get' => Get::class,
        'set' => Set::class
    ];

    /**
     *
     * @param Redis $requestProto
     * @return null|MethodParser
     */
    public static function getInstance($requestProto)
    {
        $method = strtolower($requestProto->data[0]);
        if(isset(self::$map[$method])) {
            $className = self::$map[$method];
            return new $className();
        } else {
            return null;
        }
    }

}