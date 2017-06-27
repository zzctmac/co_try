<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-6-26
 * Time: 16:57
 */

namespace ct\storage;


use ct\protocol\Simple;
use Swoole\Atomic;

class Handler
{
    /**
     * @var Atomic
     */
    public static $atomic;

    /**
     * @param Simple $simple
     * @return Simple
     */
    public static function handle(Simple $simple)
    {
        $method = strtolower($simple->method);
        $ret = null;
        switch ($method) {
            case 'get':
               $ret = Simple::create('ret', [self::$atomic->get()]);
               break;
            case 'add':
                if(count($simple->args) == 0) {
                    $ret = Simple::create('error', ['param is less']);
                } else {
                    self::$atomic->add($simple->args[0] + 0);
                    $ret = Simple::create('success');
                }
                break;
            case 'sub':
                if(count($simple->args) == 0) {
                    $ret = Simple::create('error', ['param is less']);
                } else {
                    self::$atomic->sub($simple->args[0] + 0);
                    $ret = Simple::create('success');
                }
                break;
            default:
                $ret = Simple::create('error', ['unknown']);
                break;

        }
        return $ret;
    }

}