<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 18:00
 */

namespace ct;


use ct\route\IBase;
use ct\socket\CoRedis;
use ct\socket\CoSimple;

class GlobalObject {
    /**
     * @var \ct\socket\Simple
     */
    public static $simple;

    /**
     * @var CoSimple
     */
    public static $coSimple;

    /**
     * @var IBase
     */
    public static $route;

    /**
     * @return socket\Simple
     */
    public static function getSimpleClient()
    {
        return self::$simple;
    }

    /**
     * @return CoSimple
     */
    public static function getCoSimpleClient()
    {
        return self::$coSimple;
    }

    /**
     * @var CoRedis
     */
    public static $coRedis;

    public static function getCoRedisClient()
    {
        return self::$coRedis;
    }

}