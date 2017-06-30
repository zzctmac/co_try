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
        'get' => Single::class,
        'set' => Success::class
    ];

    /**
     *
     * @param $firstChar
     * @param Redis $requestProto
     * @return MethodParser|false
     */
    public static function getInstance($firstChar, $requestProto)
    {
        switch ($firstChar) {
            case '$':
                return new Single();
            case '+':
                return new Success();
            case '*':
                return new Multi();
            case '-':
                return new Fail();
            default:
                return false;
        }
    }

}